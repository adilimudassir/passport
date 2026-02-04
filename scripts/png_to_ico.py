import sys
import struct

def main():
    if len(sys.argv) < 3:
        print("Usage: python png_to_ico.py <input_png> <output_ico>")
        sys.exit(1)

    input_path = sys.argv[1]
    output_path = sys.argv[2]

    try:
        with open(input_path, 'rb') as f:
            png_data = f.read()
    except FileNotFoundError:
        print(f"Error: Input file '{input_path}' not found.")
        sys.exit(1)

    # Basic check for PNG signature
    if not png_data.startswith(b'\x89PNG\r\n\x1a\n'):
        print("Error: Input file is not a valid PNG.")
        sys.exit(1)

    # Extract width and height from IHDR chunk
    # IHDR is at offset 8, length 13.
    # Structure: Length (4 bytes), ChunkType (4 bytes), Width (4 bytes), Height (4 bytes), ...
    w, h = struct.unpack('>II', png_data[16:24])
    
    # ICO allows max 256x256. If larger, we really should resize, but 
    # for this simple script assuming the input is the largest size intended for the ICO.
    # However, standard ICONDIR entry uses 0 for 256.
    
    width_byte = w if w < 256 else 0
    height_byte = h if h < 256 else 0

    # ICO Header (ICONDIR)
    # Reserved (2 bytes), Type (2 bytes, 1=ICO), Count (2 bytes, 1 image)
    ico_header = struct.pack('<HHH', 0, 1, 1)

    # Image Directory Entry (ICONDIRENTRY)
    # Width (1), Height (1), Palette (1), Reserved (1), Planes (2), BPP (2), Size (4), Offset (4)
    file_size = len(png_data)
    offset = 6 + 16 # Header (6) + 1 Entry (16)
    
    entry = struct.pack('<BBBBHHII', 
                        width_byte, 
                        height_byte, 
                        0, # Palette
                        0, # Reserved
                        1, # Planes
                        32, # BPP
                        file_size, 
                        offset)

    with open(output_path, 'wb') as f:
        f.write(ico_header)
        f.write(entry)
        f.write(png_data)

    print(f"Successfully created {output_path} from {input_path}")

if __name__ == "__main__":
    main()
