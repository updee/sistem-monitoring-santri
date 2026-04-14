import { test as setup, expect } from '@playwright/test';
import path from 'path';

const CREDENTIALS = {
  admin: { email: 'admin@ibs-ashiddiqi.sch.id', password: 'admin123' },
  ustadz: { email: 'ahmad.fauzi@ibs-ashiddiqi.sch.id', password: 'ustadz123' },
  wali: { email: 'hendra.gunawan@gmail.com', password: 'wali123' },
};

for (const [role, creds] of Object.entries(CREDENTIALS)) {
  setup(`authenticate as ${role}`, async ({ page }) => {
    await page.goto('/login');
    await page.locator('input[name="email"]').fill(creds.email);
    await page.locator('input[name="password"]').fill(creds.password);
    await page.locator('button[type="submit"]').click();
    await page.waitForURL(/\/(admin|ustadz|wali)\//);
    const storagePath = path.join(__dirname, `.auth/${role}.json`);
    await page.context().storageState({ path: storagePath });
  });
}
