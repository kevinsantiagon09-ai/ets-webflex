/// <reference types="vite/client" />
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'

createInertiaApp({
    title: title => `CashTrackr - ${title}`,
    pages: {
        path: './pages',
        extension: '.tsx',
    },
    setup({ el, App, props }) {
        if (el) {
            createRoot(el).render(<App {...props} />)
        }
    },
})
