import { test, expect } from '@playwright/test';
import { storageState, futureDate } from './helpers';

test.use({ storageState: storageState('wali') });

test.describe('D. WALI SANTRI', () => {

  test('Dashboard wali tampil — santri terhubung → ringkasan tampil', async ({ page }) => {
    const resp = await page.goto('/wali/dashboard');
    await expect(page).toHaveURL(/\/wali\/dashboard/);
    expect(resp?.status()).toBeLessThan(500);
    const body = await page.textContent('body');
    expect(body).not.toContain('Server Error');
    // Wali hendra.gunawan should be connected to santri Ahmad Zaki Mubarak
    expect(body).toContain('Ahmad');
  });

  // ── Monitoring pages ──────────────────────────────────────────
  test.describe('Monitoring pages', () => {
    test('/wali/hafalan dapat dibuka', async ({ page }) => {
      const resp = await page.goto('/wali/hafalan');
      await expect(page).toHaveURL(/\/wali\/hafalan/);
      expect(resp?.status()).toBeLessThan(500);
    });

    test('/wali/kehadiran dapat dibuka', async ({ page }) => {
      const resp = await page.goto('/wali/kehadiran');
      await expect(page).toHaveURL(/\/wali\/kehadiran/);
      expect(resp?.status()).toBeLessThan(500);
    });

    test('/wali/pelanggaran dapat dibuka', async ({ page }) => {
      const resp = await page.goto('/wali/pelanggaran');
      await expect(page).toHaveURL(/\/wali\/pelanggaran/);
      expect(resp?.status()).toBeLessThan(500);
    });

    test('/wali/pencapaian dapat dibuka', async ({ page }) => {
      const resp = await page.goto('/wali/pencapaian');
      await expect(page).toHaveURL(/\/wali\/pencapaian/);
      expect(resp?.status()).toBeLessThan(500);
    });
  });

  // ── Izin Wali ──────────────────────────────────────────────
  test.describe('Izin Wali', () => {
    test('buat izin baru → status menunggu', async ({ page }) => {
      await page.goto('/wali/izin/create');
      await expect(page).toHaveURL(/\/wali\/izin\/create/);
      
      const startDate = futureDate(3);
      const endDate = futureDate(5);
      
      await page.locator('input[name="tanggal_mulai"]').fill(startDate);
      await page.locator('input[name="tanggal_kembali"]').fill(endDate);
      await page.locator('textarea[name="alasan"]').fill('E2E Test - Keperluan keluarga mendesak');
      
      const namaPenjemput = page.locator('input[name="nama_penjemput"]');
      if (await namaPenjemput.count() > 0) {
        await namaPenjemput.fill('Bapak Test');
      }
      
      const noTelpPenjemput = page.locator('input[name="no_telepon_penjemput"]');
      if (await noTelpPenjemput.count() > 0) {
        await noTelpPenjemput.fill('081234567890');
      }
      
      await page.locator('button.btn-hijau[type="submit"]').click();
      await page.waitForLoadState('networkidle');
      
      const body = await page.textContent('body');
      expect(body).toContain('berhasil');
    });

    test('lihat riwayat izin', async ({ page }) => {
      const resp = await page.goto('/wali/izin');
      await expect(page).toHaveURL(/\/wali\/izin/);
      expect(resp?.status()).toBeLessThan(500);
    });

    test('detail izin dapat dibuka', async ({ page }) => {
      await page.goto('/wali/izin');
      await page.waitForLoadState('networkidle');
      
      const detailLink = page.locator('a[href*="/wali/izin/"]').first();
      if (await detailLink.count() > 0) {
        const resp = await detailLink.click().then(() => page.waitForLoadState('networkidle'));
        const body = await page.textContent('body');
        expect(body).not.toContain('Server Error');
      }
    });
  });
});
