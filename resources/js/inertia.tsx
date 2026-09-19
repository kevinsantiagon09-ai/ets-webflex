/// <reference types="vite/client" />
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'

createInertiaApp({
    title: (title) => title,
    resolve: (name) => {
        const pages = import.meta.glob('./pages/**/*.tsx')
        const page = pages[`./pages/${name}.tsx`]

        if (!page) {
            throw new Error(`Page not found: ${name}`)
        }

        return page()
    },
    setup({ el, App, props }) {
        if (el) {
            createRoot(el).render(<App {...props} />)
        }
    },
})
