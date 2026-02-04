# Technical Requirements: Passport Data Capture Application
**Version:** 1.1
**Date:** 2026-02-03

## 1. Project Overview
The **Passport Data Capture Application** is a localized desktop application designed for the rapid intake of passport data via magnetic stripe readers (MSR) or keyboard-wedge scanners. The system is bundled as a standalone installable application using NativePHP and SQLite, optimized for high-throughput offline environments.

## 2. System Architecture
- **Backend:** Laravel (PHP)
- **Frontend:** Blade Templates + Livewire
- **Database:** SQLite (Embedded)
- **Distribution:** NativePHP (Electron/Tauri wrapper)
- **Target OS:** Windows / macOS
- **Input Hardware:** Magnetic Stripe Reader (MSR) configured as HID Keyboard

## 3. Functional Requirements

### 3.1 Data Capture Module
The core function of the application.
- **Workflow:**
    1.  User selects specific **Local Government Area (LGA)** from a dropdown (Uses `Jajo\NG` library).
    2.  User focuses the "Passport Data" input field.
    3.  User swipes the passport.
    4.  System parses the raw string string, saves the record, and redirects back to the capture screen for the next swipe.
- **Auto-Submit:** The scanner should be configured to append an 'Enter' key event to trigger form submission automatically.
- **Parsing Logic (Current Implementation):**
    - The system expects a comma-separated string (CSV format).
    - **Delimiter:** `,` (Comma)
    - **Value Prefix:** The system assumes values are prefixed (e.g., "1: DOE") and strips the first 3 characters of specific fields using `substr($val, 3)`.

### 3.2 Data Management (Dashboard)
- **Live Listing:** A reactive table displaying captured records.
- **Search Capabilities:**
    - Global search bar filtering by: `lastname`, `givennames`, `gender`, `date_of_birth`, `nationality`, `passport_number`, `expiry_date`.
- **Pagination:** 10 records per page by default.
- **Export:**
    - **XLSX:** Full manifest download using `Maatwebsite\Excel`.
    - **PDF:** Printable manifest using `barryvdh/laravel-dompdf`.
- **Maintenance:**
    - **Single Delete:** Remove individual mistake entries.
    - **Bulk Delete:** "Delete All" button with SweetAlert confirmation to clear the `passports` table (`truncate`).



## 4. Data Dictionary & Schema
The `passports` table stores the captured data.

| Field | Type | Description | Source Index (Raw String) | Transformation |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT` | Primary Key | N/A | Auto-increment |
| `lga` | `STRING` | Local Government Area | Form Selection | Direct |
| `lastname` | `STRING` | Surname | Index 1 | `substr(x, 3)` |
| `givennames`| `STRING` | First/Middle Names | Index 2 | `substr(x, 3)` |
| `gender` | `STRING` | M/F | Index 3 | `substr(x, 3)` |
| `date_of_birth`| `STRING`| DOB (Format varies) | Index 4 | `substr(x, 3)` |
| `expiry_date` | `STRING` | Expiry Date | Index 5 | `substr(x, 3)` |
| `passport_number`| `STRING`| Passport Num | Index 6 | `substr(x, 3)` |
| `nationality` | `STRING` | Country Code | Index 8 | `substr(x, 3)` |
| `created_at` | `TIMESTAMP`| Capture Time | System | Now() |

## 5. Non-Functional Requirements
- **Performance:** low-latency capture cycle (< 2 seconds from swipe to next ready state).
- **Feedback:** "Toast" (SweetAlert) notifications for duplicate entries or errors to prevent operator confusion.
- **Accessibility:** Keyboard-first navigation for the capture form.

## 6. Known Limitations & Recommendations
- **Brittle Parsing:** The current parsing logic (`explode` + `substr`) is fragile and permanently coupled to a specific scanner configuration.
    - *Risk:* Changing scanner hardware will break the app.
    - *Improvement:* Switch to a regex-based parser that identifies fields by key identifiers (if available) or implements standard ICAO 9303 MRZ parsing if the scanner supports it.
- **Data Validation:** Currently relies on database constraints to catch duplicates.
    - *Improvement:* Implement Laravel FormRequest validation to check for valid date formats and passport number patterns before DB insertion.
- **Persistence:** LGA selection relies on LocalStorage in the browser view (`localStorage.getItem('lga')`) to re-populate the dropdown.
