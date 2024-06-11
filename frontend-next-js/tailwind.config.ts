import type { Config } from 'tailwindcss'

const config: Config = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-conic':
          'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
      },
    },
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: [
      {
        mytheme: {
          "primary": "#ff4740",
          "secondary": "#ff7858",
          "accent": "#730059",
          "neutral": "#0B4A69",
          "base-100": "#ffffff",
          "info": "#00b5ff",
          "success": "#00a96e",
          "warning": "#730059" || "#ffbe00",
          "error": "#ff5861",
        },
      },
    ],
  },
}

export default config
