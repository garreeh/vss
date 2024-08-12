import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'io.ionic.starter',
  appName: 'VSS',
  webDir: 'dist',
  server: {
    url: 'http://192.168.100.111:8100',
    cleartext: true
  }
};

export default config;
