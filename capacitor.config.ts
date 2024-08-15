import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'io.ionic.starter',
  appName: 'VSS',
  webDir: 'dist',
  server: {
    url: 'https://vssph.com/',
    cleartext: true
  }
};

export default config;
