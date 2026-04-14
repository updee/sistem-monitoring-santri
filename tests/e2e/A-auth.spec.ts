import { test, expect } from '@playwright/test';
import { CREDS, login, storageState } from './helpers';

test.describe('A. AUTH — semua role', () => {

  test('GET /login tampil', async ({ page }) => {
    await page.goto('/login');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await expect(page.locator('button[type="submit"]')).toBeVisible();
  });

  test('Login sukses admin → redirect admin.dashboard', async ({ page }) => {
    await login(page, CREDS.admin.email, CREDS.admin.password);
    await page.waitForURL('**/admin/dashboard');
    await expect(page).toHaveURL(/\/admin\/dashboard/);
  });

  test('Login sukses ustadz → redirect ustadz.dashboard', async ({ page }) => {
    await login(page, CREDS.ustadz.email, CREDS.ustadz.password);
    await page.waitForURL('**/ustadz/dashboard');
    await expect(page).toHaveURL(/\/ustadz\/dashboard/);
  });

  test('Login sukses wali → redirect wali.dashboard', async ({ page }) => {
    await login(page, CREDS.wali.email, CREDS.wali.password);
    await page.waitForURL('**/wali/dashboard');
    await expect(page).toHaveURL(/\/wali\/dashboard/);
  });

  test('Logout POST /logout → kembali ke login', async ({ page }) => {
    await login(page, CREDS.admin.email, CREDS.admin.password);
    await page.waitForURL('**/admin/dashboard');

    // Submit logout form via page evaluation
    await page.evaluate(() => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/logout';
      const csrf = document.querySelector('meta[name="csrf-token"]');
      if (csrf) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_token';
        input.value = csrf.getAttribute('content')!;
        form.appendChild(input);
      }
      document.body.appendChild(form);
      form.submit();
    });
    await page.waitForURL('**/login');
    await expect(page).toHaveURL(/\/login/);
  });

  test('Akun nonaktif (is_active=false) dipaksa logout + pesan error', async ({ page }) => {
    // This test uses the middleware check. We'll create a special inactive user test
    // by first logging in as admin and deactivating a user, then trying to access as that user.
    // For simplicity we test the flow: login as admin, toggle a wali inactive,
    // then try to access wali pages -- should be kicked out.
    
    // First login as admin
    await login(page, CREDS.admin.email, CREDS.admin.password);
    await page.waitForURL('**/admin/dashboard');
    
    // Go to user management, find and deactivate a test wali (we use a second wali)
    await page.goto('/admin/users?search=siti.rahayu');
    await page.waitForLoadState('networkidle');
    
    // Find the toggle button for this user
    const toggleForm = page.locator('form[action*="toggle-active"]').first();
    if (await toggleForm.count() > 0) {
      await toggleForm.locator('button').click();
      await page.waitForLoadState('networkidle');
    }
    
    // Now logout admin
    await page.evaluate(() => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/logout';
      const csrf = document.querySelector('meta[name="csrf-token"]');
      if (csrf) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_token';
        input.value = csrf.getAttribute('content')!;
        form.appendChild(input);
      }
      document.body.appendChild(form);
      form.submit();
    });
    await page.waitForURL('**/login');
    
    // Login as the deactivated wali
    await login(page, 'siti.rahayu@gmail.com', 'wali123');
    // Should either fail to login or be kicked out by middleware
    await page.waitForLoadState('networkidle');
    
    // The RoleMiddleware forces logout with error message
    // After login, the user will be redirected to dashboard, but middleware kicks them back
    const currentUrl = page.url();
    if (currentUrl.includes('/login')) {
      // Check for error message
      await expect(page.locator('.alert-error, .alert-danger, [class*="alert"]')).toBeVisible();
    } else {
      // They got past login - try accessing a protected page
      await page.goto('/wali/dashboard');
      await expect(page).toHaveURL(/\/login/);
    }
    
    // Re-activate the user (cleanup)
    await login(page, CREDS.admin.email, CREDS.admin.password);
    await page.waitForURL('**/admin/dashboard');
    await page.goto('/admin/users?search=siti.rahayu');
    await page.waitForLoadState('networkidle');
    const reToggle = page.locator('form[action*="toggle-active"]').first();
    if (await reToggle.count() > 0) {
      await reToggle.locator('button').click();
      await page.waitForLoadState('networkidle');
    }
  });
});
