export const mediamanagerLightTheme = {
    dark: false,
    colors: {
        'mm-primary': '#5C6BC0', // soft indigo (modern, friendly primary)
        'mm-secondary': '#90A4AE', // cool grey (gentle secondary, not dominant)
        'mm-accent': '#26C6DA', // teal-cyan accent (fresh, but not neon)
        'mm-background': '#F9FAFB', // very light grey background (not pure white)
        'mm-surface': '#FFFFFF', // white cards/surfaces for clarity
        'mm-text': '#37474F', // dark blue-grey (softer than pure black)
        'mm-success': '#43A047', // calm green, not too flashy
        'mm-error': '#E57373', // soft red, less aggressive than #E53935
    },
}

export const mediamanagerDarkTheme = {
    dark: true,
    colors: {
        'mm-primary': '#9FA8DA', // lighter indigo for dark backgrounds
        'mm-secondary': '#B0BEC5', // soft grey, matches light theme secondary
        'mm-accent': '#4DD0E1', // teal-cyan, brighter for contrast
        'mm-background': '#121212', // modern dark background (material standard)
        'mm-surface': '#1E1E1E', // slightly lighter cards/surfaces
        'mm-text': '#ECEFF1', // very light grey, soft on the eyes
        'mm-success': '#66BB6A', // balanced green for dark UI
        'mm-error': '#EF9A9A', // softer red, not harsh against dark bg
    },
}

export default {
    install(app) {
        app.config.globalProperties.$mmThemes = {
            light: mediamanagerLightTheme,
            dark: mediamanagerDarkTheme,
        };
    }
};