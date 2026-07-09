import { chromium } from 'playwright';
import path from 'path';
import fs from 'fs';
import { execFileSync } from 'child_process';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const baseUrl = process.env.BASE_URL || 'http://127.0.0.1:8000';
const screenshotsDir = path.join(__dirname, 'screenshots');
const cleanupCommand = [
    'artisan',
    'e2e:cleanup',
];

if (!fs.existsSync(screenshotsDir)) {
    fs.mkdirSync(screenshotsDir, { recursive: true });
}

console.log('=== MEMULAI PENGUJIAN WEB E2E VITALFLOW ===');
console.log(`Target URL: ${baseUrl}`);
console.log(`Folder Screenshot: ${screenshotsDir}\n`);

async function run() {
    console.log('[-] Membersihkan data pasien E2E lama...');
    execFileSync('php', cleanupCommand, { cwd: path.join(__dirname, '..', '..'), stdio: 'ignore' });

    console.log('[-] Meluncurkan browser otomatis...');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1280, height: 800 }
    });
    const page = await context.newPage();

    try {
        // 1. Membuka Halaman Login
        console.log('[1] Membuka Halaman Login...');
        await page.goto(baseUrl);
        await page.waitForLoadState('networkidle');

        // Screenshot 1: Login
        const loginSS = path.join(screenshotsDir, '01_login_page.png');
        await page.screenshot({ path: loginSS });
        console.log(`    -> [SUKSES] Screenshot login disimpan: ${loginSS}`);

        // 2. Mengisi Kredensial Login
        console.log('[2] Mengisi Form Kredensial NIP & Password...');
        await page.fill('#employee_id', '2405001');
        await page.fill('#password', 'password');

        console.log("    -> Mengklik tombol 'Masuk Sekarang'...");
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.getByRole('button', { name: 'Masuk Sekarang' }).click(),
        ]);

        console.log('    -> Menunggu pengalihan ke Dashboard...');
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Screenshot 2: Dashboard
        const dashboardSS = path.join(screenshotsDir, '02_dashboard.png');
        await page.screenshot({ path: dashboardSS });
        console.log(`    -> [SUKSES] Screenshot dashboard disimpan: ${dashboardSS}`);
        console.log(`    -> URL saat ini: ${page.url()}`);

        // 3. Navigasi ke Halaman Tambah Pasien
        console.log('[3] Membuka Halaman Input Pasien Baru...');
        await page.goto(`${baseUrl}/patients/create`);
        await page.waitForLoadState('networkidle');
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Screenshot 3: Input Pasien
        const inputSS = path.join(screenshotsDir, '03_patient_input.png');
        await page.screenshot({ path: inputSS });
        console.log(`    -> [SUKSES] Screenshot form input disimpan: ${inputSS}`);

        // 4. Mengisi Data Pasien Baru
        console.log('[4] Mengisi Data Identitas Pasien & Detail Pemasangan Infus...');
        await page.fill('#patient_name', 'E2E Pasien Uji');
        await page.fill('#room_name', 'E2E Bed 01');
        await page.selectOption('#bed_number', '1');
        await page.fill('#doctor_name', 'dr. E2E, Sp.A');
        await page.fill('#nurse_name', 'Suster Amira');
        await page.fill('#initial_volume', '500');
        await page.fill('#installed_at', '2026-05-20T12:00');

        // Screenshot 4: Form Terisi
        const filledSS = path.join(screenshotsDir, '04_patient_input_filled.png');
        await page.screenshot({ path: filledSS });
        console.log(`    -> [SUKSES] Screenshot form terisi disimpan: ${filledSS}`);

        // 5. Menyimpan Data & Memulai Monitoring
        console.log("[5] Mengklik tombol 'Simpan & Mulai Monitoring'...");
        page.once('dialog', async dialog => {
            console.log(`    -> Dialog konfirmasi muncul: ${dialog.message()}`);
            await dialog.accept();
        });
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.getByRole('button', { name: 'Simpan & Mulai Monitoring' }).click(),
        ]);
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Screenshot 5: Hasil Submit
        const resultSS = path.join(screenshotsDir, '05_submitted_result.png');
        await page.screenshot({ path: resultSS });
        console.log(`    -> [SUKSES] Screenshot hasil akhir disimpan: ${resultSS}`);
        console.log(`    -> URL setelah submit: ${page.url()}`);

        if (!page.url().includes('/monitoring')) {
            throw new Error(`Submit pasien tidak masuk ke halaman monitoring. URL saat ini: ${page.url()}`);
        }

        console.log('\n=== PENGUJIAN WEB E2E SELESAI DENGAN SUKSES! ===');
    } catch (e) {
        console.log(`\n[ERROR] Pengujian gagal: ${e.message}`);
        const errorSS = path.join(screenshotsDir, 'error_screenshot.png');
        await page.screenshot({ path: errorSS });
        console.log(`Screenshot error disimpan ke: ${errorSS}`);
        process.exit(1);
    } finally {
        await browser.close();
        execFileSync('php', cleanupCommand, { cwd: path.join(__dirname, '..', '..'), stdio: 'ignore' });
    }
}

run();
