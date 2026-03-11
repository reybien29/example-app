import type { Config } from 'tailwindcss';

const config: Config = {
  content: [
    './app/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './lib/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        ink: {
          DEFAULT: '#0f1117',
          muted: '#6b7280',
          faint: '#d1d5db',
        },
        surface: {
          DEFAULT: '#ffffff',
          raised: '#f9fafb',
          hover: '#f3f4f6',
        },
        accent: {
          DEFAULT: '#2563eb',
          light: '#eff6ff',
        },
        danger: {
          DEFAULT: '#dc2626',
          light: '#fef2f2',
        },
        success: {
          DEFAULT: '#16a34a',
          light: '#f0fdf4',
        },
        sidebar: {
          bg: '#0f1117',
          text: '#e2e8f0',
          muted: '#64748b',
          hover: '#1e2330',
          active: '#1d4ed8',
        },
      },
      fontFamily: {
        sans: ['DM Sans', 'system-ui', 'sans-serif'],
        serif: ['DM Serif Display', 'Georgia', 'serif'],
      },
      borderRadius: {
        DEFAULT: '10px',
      },
      animation: {
        'fade-up': 'fadeUp 0.35s ease both',
        'slide-down': 'slideDown 0.3s ease',
        'slide-in': 'slideIn 0.25s ease',
      },
      keyframes: {
        fadeUp: {
          from: { opacity: '0', transform: 'translateY(10px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        slideDown: {
          from: { opacity: '0', transform: 'translateY(-8px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        slideIn: {
          from: { opacity: '0', transform: 'translateX(16px)' },
          to: { opacity: '1', transform: 'translateX(0)' },
        },
      },
    },
  },
  plugins: [],
};

export default config;
