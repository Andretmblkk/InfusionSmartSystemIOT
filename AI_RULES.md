# AI DEVELOPMENT RULES

## PROJECT OVERVIEW

Framework Stack:
- Laravel
- Inertia React
- Tailwind CSS

Tujuan project:
Membangun aplikasi monitoring rumah sakit dengan UI yang semirip mungkin dengan desain referensi pada folder `/reference`.

Prioritas utama:
1. Konsistensi visual
2. Reusable component
3. Layout yang clean dan modern
4. Struktur code yang maintainable
5. Akurasi visual terhadap referensi

---

# DESIGN REFERENCES

Semua referensi UI terdapat pada folder:

/reference

Daftar file referensi:
- dashboard.png
- login.png
- patient-input.png
- monitoring.png
- monitoring-history.png

WAJIB:
- Mengikuti layout semirip mungkin
- Mengikuti spacing
- Mengikuti alignment
- Mengikuti hierarchy visual
- Mengikuti ukuran element
- Mengikuti typography
- Mengikuti style card
- Mengikuti warna utama
- Mengikuti struktur sidebar/navbar

DILARANG:
- Improvisasi layout baru
- Mengubah struktur visual utama
- Menggunakan style random
- Mengubah hierarchy visual tanpa alasan

---

# MCP INTEGRATION

Gunakan Stitch MCP sebagai sumber struktur design utama.

Gunakan folder `/reference` sebagai validasi visual akhir.

Jika terdapat perbedaan antara:
- hasil generate dari MCP
- dan gambar referensi

Maka:
- prioritaskan kecocokan visual dengan gambar referensi
- lakukan refinement sampai hasil mendekati pixel-perfect

---

# GLOBAL DESIGN SYSTEM

Font:
- Inter

Design Style:
- Modern dashboard
- Clean UI
- Professional medical dashboard
- Soft shadow
- Medium rounded corner
- Minimalist layout

Dominant Spacing:
- 16px
- 24px
- 32px

Border Radius:
- 12px

Visual Style:
- Soft card shadow
- Clean whitespace
- Consistent spacing
- Consistent icon sizing
- Neutral modern color palette

---

# LAYOUT CONSISTENCY

Semua halaman HARUS memiliki:
- sidebar style konsisten
- navbar style konsisten
- spacing konsisten
- typography konsisten
- card style konsisten
- button style konsisten
- form style konsisten

Gunakan shared layout.

Jangan membuat ulang:
- sidebar
- navbar
- topbar
- card component
- button component

untuk setiap halaman.

---

# DEVELOPMENT RULES

WAJIB:
- Gunakan reusable components
- Gunakan Tailwind CSS
- Gunakan responsive layout
- Gunakan clean component structure
- Pisahkan layout, page, dan component
- Gunakan semantic naming
- Gunakan consistent spacing
- Gunakan consistent typography

DILARANG:
- Duplicate component
- Hardcode style berbeda antar halaman
- Inline style berlebihan
- Random spacing
- Random font sizing
- Random border radius
- Membuat style baru yang tidak ada di referensi

---

# FOLDER STRUCTURE

Gunakan struktur berikut:

resources/js/
├── Pages
├── Components
├── Layouts
├── Hooks
└── Utils

Reusable components harus berada di:

resources/js/Components

Shared layout harus berada di:

resources/js/Layouts

---

# COMPONENT PRIORITY

Jika terdapat elemen UI yang berulang:
- Sidebar
- Navbar
- Stats Card
- Table
- Button
- Form Input
- Modal
- Badge
- Header Section

WAJIB dijadikan reusable component.

Jangan duplicate component antar halaman.

---

# PAGE GENERATION WORKFLOW

Saat membuat halaman:

1. Baca file referensi terkait di folder `/reference`
2. Analisis:
   - layout
   - spacing
   - alignment
   - typography
   - warna
   - ukuran element
   - card structure
   - navbar/sidebar structure
3. Gunakan Stitch MCP untuk memahami struktur design
4. Buat implementasi semirip mungkin
5. Gunakan reusable component jika memungkinkan
6. Setelah generate awal selesai:
   - lakukan compare dengan gambar referensi
   - identifikasi perbedaan visual
   - lakukan refinement

WAJIB melakukan refinement minimal 1x setelah generate awal.

---

# VISUAL AUDIT

Sebelum menyelesaikan halaman:

Lakukan audit visual terhadap:
- alignment
- spacing
- typography
- ukuran icon
- ukuran card
- ukuran table
- navbar
- sidebar
- warna
- shadow
- border radius
- visual hierarchy

Bandingkan hasil implementasi dengan gambar referensi secara detail.

Perbaiki semua perbedaan visual yang signifikan.

---

# COMPARISON INSTRUCTION

Setelah halaman selesai dibuat:

WAJIB:
1. Compare hasil implementasi dengan gambar referensi
2. Cari semua perbedaan visual
3. Refine layout dan styling
4. Pastikan visual mendekati pixel-perfect

Fokus compare:
- padding
- margin
- font size
- spacing
- alignment
- card size
- button size
- icon size
- sidebar width
- navbar height
- table spacing
- shadow
- border radius

---

# RESPONSIVE RULES

Layout harus responsive untuk:
- desktop
- tablet
- mobile

Namun:
prioritas utama tetap akurasi desain desktop sesuai referensi.

---

# OUTPUT EXPECTATION

Hasil akhir HARUS:
- clean
- modern
- professional
- reusable
- maintainable
- responsive
- visually consistent
- mendekati desain referensi semaksimal mungkin

Target utama:
pixel-perfect semaksimal mungkin terhadap folder `/reference`.