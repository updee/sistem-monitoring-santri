import { Page, expect } from '@playwright/test';
import path from 'path';

export const AUTH_DIR = path.join(__dirname, '.auth');

export function storageState(role: 'admin' | 'ustadz' | 'wali') {
  return path.join(AUTH_DIR, `${role}.json`);
}

export const CREDS = {
  admin: { email: 'admin@ibs-ashiddiqi.sch.id', password: 'admin123' },
  ustadz: { email: 'ahmad.fauzi@ibs-ashiddiqi.sch.id', password: 'ustadz123' },
  wali: { email: 'hendra.gunawan@gmail.com', password: 'wali123' },
};

export async function login(page: Page, email: string, password: string) {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill(email);
  await page.locator('input[name="password"]').fill(password);
  await page.locator('button[type="submit"]').click();
}

export async function logout(page: Page) {
  const csrfToken = await page.locator('meta[name="csrf-token"]').getAttribute('content');
  if (csrfToken) {
    await page.request.post('/logout', {
      headers: { 'X-CSRF-TOKEN': csrfToken },
    });
  }
}

export function today(): string {
  return new Date().toISOString().split('T')[0];
}

export function futureDate(days: number): string {
  const d = new Date();
  d.setDate(d.getDate() + days);
  return d.toISOString().split('T')[0];
}
