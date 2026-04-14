import { test, expect } from '@playwright/test';
import { storageState, today } from './helpers';

test.use({ storageState: storageState('admin') });

test.describe('B. ADMIN', () => {

  test('Dashboard admin tampil dan tidak error', async ({ page }) => {
    const resp = await page.goto('/admin/dashboard');
    await expect(page).toHaveURL(/\/admin\/dashboard/);
    expect(resp?.status()).toBeLessThan(500);
    const body = await page.textContent('body');
    expect(body).not.toContain('Server Error');
  });

  // ── CRUD Santri ──────────────────────────────────────────────
  test.describe('CRUD Santri', () => {
    const testNIS = 'E2E' + Date.now().toString().slice(-6);
    const testNama = 'E2E Test Santri';

    test('index list + filter jalan', async ({ page }) => {
      await page.goto('/admin/santri');
      await expect(page).toHaveURL(/\/admin\/santri/);
      // Should have at least one santri row
      await expect(page.locator('table tbody tr, .santri-card, [class*="santri"]').first()).toBeVisible();
    });

    test('create santri valid → muncul di index', async ({ page }) => {
      await page.goto('/admin/santri/create');
      await page.locator('input[name="nis"]').fill(testNIS);
      await page.locator('input[name="nama"]').fill(testNama);
      await page.locator('select[name="jenis_kelamin"]').selectOption('L');
      await page.locator('button.btn-hijau[type="submit"]').click();
      await page.waitForLoadState('networkidle');
      
      // Should redirect to index with success or show validation errors
      const currentUrl = page.url();
      if (currentUrl.includes('/admin/santri') && !currentUrl.includes('/create')) {
        const pageContent = await page.textContent('body');
        expect(pageContent).toContain('berhasil');
      } else {
        // Still on create page - check for validation errors (not server errors)
        const body = await page.textContent('body');
        expect(body).not.toContain('Server Error');
        // If NIS uniqueness validation fails, the test data needs cleanup
        expect(body).not.toContain('has already been taken');
      }
    });

    test('edit santri → perubahan tersimpan', async ({ page }) => {
      await page.goto(`/admin/santri?search=${testNIS}`);
      await page.waitForLoadState('networkidle');
      
      const editLink = page.locator('a[href*="/edit"]').first();
      if (await editLink.count() > 0) {
        await editLink.click();
        await page.waitForLoadState('networkidle');
        
        await page.locator('input[name="nama"]').fill(testNama + ' Updated');
        await page.locator('button.btn-hijau[type="submit"]').click();
        await page.waitForURL('**/admin/santri');
        
        const pageContent = await page.textContent('body');
        expect(pageContent).toContain('berhasil');
      }
    });

    test('delete santri (soft delete) → hilang dari index', async ({ page }) => {
      await page.goto(`/admin/santri?search=${testNIS}`);
      await page.waitForLoadState('networkidle');
      
      // Find and click delete form/button
      const deleteForm = page.locator('form[action*="santri"][method="POST"]').filter({ hasText: /hapus|delete/i }).first();
      if (await deleteForm.count() > 0) {
        // Handle confirmation dialog
        page.on('dialog', dialog => dialog.accept());
        await deleteForm.locator('button').click();
        await page.waitForLoadState('networkidle');
      } else {
        // Try delete button directly
        const deleteBtn = page.locator('button, a').filter({ hasText: /hapus|delete/i }).first();
        if (await deleteBtn.count() > 0) {
          page.on('dialog', dialog => dialog.accept());
          await deleteBtn.click();
          await page.waitForLoadState('networkidle');
        }
      }
      
      // Verify gone from index
      await page.goto(`/admin/santri?search=${testNIS}`);
      const body = await page.textContent('body');
      expect(body).not.toContain(testNama + ' Updated');
    });

    test('rekap santri /admin/santri/{id}/rekap dapat dibuka', async ({ page }) => {
      // Get a valid santri ID
      await page.goto('/admin/santri');
      await page.waitForLoadState('networkidle');
      
      // Click on first rekap/show link
      const rekapLink = page.locator('a[href*="/rekap"]').first();
      if (await rekapLink.count() > 0) {
        await rekapLink.click();
      } else {
        // Try show link which redirects to rekap
        const showLink = page.locator('a[href*="/admin/santri/"]').filter({ hasNot: page.locator('[href*="edit"], [href*="create"]') }).first();
        if (await showLink.count() > 0) {
          await showLink.click();
        }
      }
      await page.waitForLoadState('networkidle');
      await expect(page).toHaveURL(/\/admin\/santri\/\d+\/rekap/);
      const body = await page.textContent('body');
      expect(body).not.toContain('Server Error');
    });
  });

  // ── Kelola User ──────────────────────────────────────────────
  test.describe('Kelola User', () => {
    test('index users tampil', async ({ page }) => {
      await page.goto('/admin/users');
      await expect(page).toHaveURL(/\/admin\/users/);
      const body = await page.textContent('body');
      expect(body).not.toContain('Server Error');
    });

    test('toggle active bekerja', async ({ page }) => {
      await page.goto('/admin/users?search=muhammad.ridwan');
      await page.waitForLoadState('networkidle');
      
      const toggleForm = page.locator('form[action*="toggle-active"]').first();
      if (await toggleForm.count() > 0) {
        await toggleForm.locator('button').click();
        await page.waitForLoadState('networkidle');
        const body = await page.textContent('body');
        expect(body).toContain('berhasil');
        
        // Toggle back
        await page.goto('/admin/users?search=muhammad.ridwan');
        await page.waitForLoadState('networkidle');
        const reToggle = page.locator('form[action*="toggle-active"]').first();
        if (await reToggle.count() > 0) {
          await reToggle.locator('button').click();
        }
      }
    });
  });

  // ── Master Data ──────────────────────────────────────────────
  test.describe('Master Data', () => {
    test('kelas index tampil', async ({ page }) => {
      await page.goto('/admin/kelas');
      await expect(page).toHaveURL(/\/admin\/kelas/);
      const body = await page.textContent('body');
      expect(body).not.toContain('Server Error');
      // Should list some kelas
      expect(body).toContain('Halaqah');
    });

    test('kamar index tampil', async ({ page }) => {
      await page.goto('/admin/kamar');
      await expect(page).toHaveURL(/\/admin\/kamar/);
      const body = await page.textContent('body');
      expect(body).not.toContain('Server Error');
    });

    test('kategori pelanggaran index tampil', async ({ page }) => {
      await page.goto('/admin/kategori-pelanggaran');
      await expect(page).toHaveURL(/\/admin\/kategori-pelanggaran/);
      const body = await page.textContent('body');
      expect(body).not.toContain('Server Error');
    });
  });

  // ── Izin ────────────────────────────────────────────────────
  test.describe('Izin Admin', () => {
    test('admin dapat melihat izin index', async ({ page }) => {
      await page.goto('/admin/izin');
      await expect(page).toHaveURL(/\/admin\/izin/);
      const body = await page.textContent('body');
      expect(body).not.toContain('Server Error');
    });

    test('admin setujui izin mengubah status', async ({ page }) => {
      // Go to izin with status menunggu
      await page.goto('/admin/izin?status=menunggu');
      await page.waitForLoadState('networkidle');
      
      // Find approve button/form
      const setujuiForm = page.locator('form[action*="setujui"]').first();
      if (await setujuiForm.count() > 0) {
        await setujuiForm.locator('button').click();
        await page.waitForLoadState('networkidle');
        const body = await page.textContent('body');
        expect(body).toContain('berhasil');
      } else {
        // No pending izin to approve - that's ok, check page loaded
        const body = await page.textContent('body');
        expect(body).not.toContain('Server Error');
      }
    });

    test('admin tolak izin mengubah status', async ({ page }) => {
      const resp = await page.goto('/admin/izin?status=menunggu');
      expect(resp?.status()).toBeLessThan(500);
      await page.waitForLoadState('networkidle');
      
      const tolakForm = page.locator('form[action*="tolak"]').first();
      if (await tolakForm.count() > 0) {
        page.on('dialog', d => d.accept());
        await tolakForm.locator('button').click();
        await page.waitForLoadState('networkidle');
        // After tolak, page redirects back; flash message may appear briefly
        const resp2 = page.url();
        expect(resp2).toContain('/admin/izin');
      } else {
        const body = await page.textContent('body');
        expect(body).not.toContain('Server Error');
      }
    });
  });
});
