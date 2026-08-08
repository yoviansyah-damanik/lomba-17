import { chromium } from 'playwright';
const base = 'http://127.0.0.1:8017';
const dir = 'C:\\Users\\it_rs\\AppData\\Local\\Temp\\claude\\d--WebApps-lomba-17\\0e86592b-68bc-4cd5-b142-d743f9a1050e\\scratchpad\\';
const shot = (n) => dir + n + '.png';
const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 480, height: 900 } });
const errors = [];
page.on('pageerror', (e) => errors.push('PAGEERROR: ' + e.message));
page.on('console', (msg) => { if (msg.type() === 'error') errors.push('CONSOLE: ' + msg.text()); });

await page.goto(`${base}/login`);
await page.fill('#username', 'juri1');
await page.fill('#password', 'password');
await page.click('button:has-text("Masuk")');
await page.waitForURL('**/dashboard');

await page.goto(`${base}/evaluation`, { waitUntil: 'domcontentloaded' });
await page.waitForSelector('#competitionId');
await page.waitForTimeout(300);
await page.selectOption('#competitionId', { label: 'Deville Pramuka' });
await page.waitForTimeout(300);
await page.fill('#npp', '001');
await page.selectOption('#school_type', 'SD');
await page.waitForTimeout(300);
await page.click('button:has-text("Cari Peserta")');
await page.waitForTimeout(800);

let resetCount = await page.locator('button:has-text("Kosongkan")').count();
console.log('reset buttons present:', resetCount);

// type into first notes textarea
const notesArea = page.locator('textarea').first();
await notesArea.fill('Catatan uji coba.');
await page.waitForTimeout(300);
await page.screenshot({ path: shot('1401-notes-filled-with-reset-btn') });

// click reset
await page.locator('button:has-text("Kosongkan")').first().click();
await page.waitForTimeout(400);
const notesValueAfterReset = await notesArea.inputValue();
console.log('notes value after reset:', JSON.stringify(notesValueAfterReset));
resetCount = await page.locator('button:has-text("Kosongkan")').count();
console.log('reset buttons after reset click:', resetCount);
await page.screenshot({ path: shot('1402-after-reset-click') });

// mic instant toggle check
const micBtn = page.locator('button[aria-label="Isi catatan dengan suara"]').first();
await micBtn.click();
const listeningImmediately = await page.locator('button[aria-label="Berhenti merekam"]').count();
console.log('shows listening state right after start click:', listeningImmediately);

const stopBtn = page.locator('button[aria-label="Berhenti merekam"]').first();
await stopBtn.click();
// check WITHOUT waiting - should already be back to non-listening state instantly
const stillListeningRightAfterClick = await page.locator('button[aria-label="Berhenti merekam"]').count();
console.log('still shows listening immediately after stop click (should be 0):', stillListeningRightAfterClick);

console.log('errors:', errors);
await browser.close();
