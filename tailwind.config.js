import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['class'],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js,ts,jsx,tsx}',
    ],
    theme: {
        extend: {
            fontFamily: {
                // Inter for UI, body text, labels
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                // Merriweather for article headlines & editorial feel
                headline: ['Merriweather', 'Georgia', ...defaultTheme.fontFamily.serif],
                serif: ['Merriweather', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Portal brand colors
                brand: {
                    50:  '#fff1f1',
                    100: '#ffe1e1',
                    200: '#ffc7c7',
                    300: '#ffa0a0',
                    400: '#ff6b6b',
                    500: '#f83b3b',
                    600: '#e51c1c',
                    700: '#c11414',
                    800: '#a01414',
                    900: '#841717',
                    950: '#480707',
                },
                // Neutral ink tones for editorial feel
                ink: {
                    50:  '#f7f7f8',
                    100: '#eeeef0',
                    200: '#d9d9de',
                    300: '#b8b9c1',
                    400: '#9193a0',
                    500: '#737582',
                    600: '#5d5f6b',
                    700: '#4c4e58',
                    800: '#41424b',
                    900: '#393a42',
                    950: '#18181d',
                },
                background: 'hsl(var(--background))',
                foreground: 'hsl(var(--foreground))',
                card: {
                    DEFAULT: 'hsl(var(--card))',
                    foreground: 'hsl(var(--card-foreground))',
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover))',
                    foreground: 'hsl(var(--popover-foreground))',
                },
                primary: {
                    DEFAULT: 'hsl(var(--primary))',
                    foreground: 'hsl(var(--primary-foreground))',
                },
                secondary: {
                    DEFAULT: 'hsl(var(--secondary))',
                    foreground: 'hsl(var(--secondary-foreground))',
                },
                muted: {
                    DEFAULT: 'hsl(var(--muted))',
                    foreground: 'hsl(var(--muted-foreground))',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent))',
                    foreground: 'hsl(var(--accent-foreground))',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive))',
                    foreground: 'hsl(var(--destructive-foreground))',
                },
                border: 'hsl(var(--border))',
                input: 'hsl(var(--input))',
                ring: 'hsl(var(--ring))',
                sidebar: {
                    DEFAULT: 'hsl(var(--sidebar-background))',
                    foreground: 'hsl(var(--sidebar-foreground))',
                    primary: 'hsl(var(--sidebar-primary))',
                    'primary-foreground': 'hsl(var(--sidebar-primary-foreground))',
                    accent: 'hsl(var(--sidebar-accent))',
                    'accent-foreground': 'hsl(var(--sidebar-accent-foreground))',
                    border: 'hsl(var(--sidebar-border))',
                    ring: 'hsl(var(--sidebar-ring))',
                },
            },
            backgroundImage: {
                'hero-gradient': 'linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.1) 100%)',
                'card-gradient': 'linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.3) 60%, transparent 100%)',
                'brand-gradient': 'linear-gradient(135deg, #c11414 0%, #e51c1c 50%, #f83b3b 100%)',
                'dark-gradient': 'linear-gradient(135deg, #18181d 0%, #393a42 100%)',
            },
            boxShadow: {
                'card-hover': '0 8px 30px rgba(0,0,0,0.12)',
                'brand': '0 4px 14px rgba(225,28,28,0.35)',
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 2px)',
                sm: 'calc(var(--radius) - 4px)',
            },
        },
    },
    plugins: [require('tailwindcss-animate')],
};
