<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Passport;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Database\QueryException;

#[Layout('layouts.app')]
#[Title('Passport Data Capture - Sokoto State')]
class PassportCapture extends Component
{
    use WithPagination;

    public string $lga = '';
    public string $rawData = '';
    public int $captureCount = 0;

    #[Url]
    public string $search = '';

    // Sokoto State LGAs
    public array $lgas = [
        'Binji',
        'Bodinga',
        'Dange Shuni',
        'Gada',
        'Goronyo',
        'Gudu',
        'Gwadabawa',
        'Illela',
        'Isa',
        'Kebbe',
        'Kware',
        'Rabah',
        'Sabon Birni',
        'Shagari',
        'Silame',
        'Sokoto North',
        'Sokoto South',
        'Tambuwal',
        'Tangaza',
        'Tureta',
        'Wamako',
        'Wurno',
        'Yabo',
    ];

    public function mount(): void
    {
        // Restore LGA from session
        $this->lga = session('passport_lga', '');
        $this->captureCount = Passport::count();
    }

    public function updatedLga(): void
    {
        // Persist LGA to session immediately
        session()->put('passport_lga', $this->lga);
        session()->save();
        
        // Show toast notification
        if ($this->lga) {
            $this->dispatch('toast', type: 'success', message: "LGA changed to {$this->lga}");
        }
        
        // Reset pagination when LGA changes
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function capture(): void
    {
        if (empty($this->lga)) {
            $this->dispatch('toast', type: 'error', message: 'Please select an LGA first');
            return;
        }

        if (empty($this->rawData)) {
            $this->dispatch('toast', type: 'error', message: 'No passport data received');
            return;
        }

        try {
            $data = $this->parseRawData($this->rawData);
            
            Passport::create([
                'lga' => $this->lga,
                'lastname' => $data['lastname'],
                'givennames' => $data['givennames'],
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'expiry_date' => $data['expiry_date'],
                'passport_number' => $data['passport_number'],
                'nationality' => $data['nationality'],
            ]);

            $this->captureCount++;
            $this->rawData = '';
            $this->dispatch('toast', type: 'success', message: 'Passport captured successfully');
            $this->dispatch('focus-input');

        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'UNIQUE constraint failed') || str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->dispatch('toast', type: 'error', message: 'Duplicate passport number detected');
            } else {
                $this->dispatch('toast', type: 'error', message: 'Database error: ' . $e->getMessage());
            }
            $this->rawData = '';
            $this->dispatch('focus-input');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Error: ' . $e->getMessage());
            $this->rawData = '';
            $this->dispatch('focus-input');
        }
    }

    public function clearInput(): void
    {
        $this->rawData = '';
        $this->dispatch('focus-input');
    }

    public function deletePassport(int $id): void
    {
        Passport::destroy($id);
        $this->captureCount = Passport::count();
        $this->dispatch('toast', type: 'success', message: 'Record deleted successfully');
    }

    public function deleteAll(): void
    {
        Passport::truncate();
        $this->captureCount = 0;
        $this->dispatch('toast', type: 'success', message: 'All records deleted');
    }

    protected function parseRawData(string $raw): array
    {
        $parts = explode(',', $raw);
        
        return [
            'lastname' => isset($parts[0]) ? substr(trim($parts[0]), 3) : '',
            'givennames' => isset($parts[1]) ? substr(trim($parts[1]), 3) : '',
            'gender' => isset($parts[2]) ? substr(trim($parts[2]), 3) : '',
            'date_of_birth' => isset($parts[3]) ? substr(trim($parts[3]), 3) : '',
            'expiry_date' => isset($parts[4]) ? substr(trim($parts[4]), 3) : '',
            'passport_number' => isset($parts[5]) ? substr(trim($parts[5]), 3) : '',
            'nationality' => isset($parts[7]) ? substr(trim($parts[7]), 3) : '',
        ];
    }

    public function render()
    {
        $query = Passport::query();

        // Filter by selected LGA
        if ($this->lga) {
            $query->where('lga', $this->lga);
        }

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('lastname', 'like', $search)
                  ->orWhere('givennames', 'like', $search)
                  ->orWhere('gender', 'like', $search)
                  ->orWhere('date_of_birth', 'like', $search)
                  ->orWhere('nationality', 'like', $search)
                  ->orWhere('passport_number', 'like', $search)
                  ->orWhere('expiry_date', 'like', $search);
            });
        }

        return view('livewire.passport-capture', [
            'passports' => $query->latest()->paginate(10),
        ]);
    }
}
