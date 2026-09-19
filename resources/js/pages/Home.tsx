import { Head, Link, usePage } from '@inertiajs/react';
import { Setting } from '../types/setting';

type Props = {
    loginUrl: string;
    adminUrl: string;
};

type PageProps = {
    auth: {
        user: any;
    };

    site: Setting;
};

export default function Home({ loginUrl, adminUrl }: Props) {
    const { auth, site } =   usePage<PageProps>().props;    
     console.log(site);
     
    return (
        <main className="min-h-screen w-full bg-white text-slate-900 dark:bg-slate-950 dark:text-white">
            <Head title={`Inicio - ${site.name}`} />

            <header className="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-6 sm:px-8 dark:border-slate-800">
                <div className="flex min-w-0 items-center gap-3">
                    {site.logoUrl && <img src={site.logoUrl} alt={site.name} className="size-12 object-contain" />}
                    <span className="break-words text-xl font-semibold">{site.name}</span>
                </div>
                <Link
                    href={auth.user ? adminUrl : loginUrl}
                    className="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-3 text-sm font-medium hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-offset-4 dark:border-slate-700 dark:hover:bg-slate-800"
                >
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="size-5">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 7l5 5-5 5M15 12H3" />
                    </svg>
                    {auth.user ? 'Administrar sitio' : 'Iniciar sesión'}
                </Link>
            </header>

            <section className="flex min-h-96 flex-col justify-center gap-5 px-6 py-16 sm:px-8">
                <p className="text-sm font-medium uppercase tracking-widest text-slate-500 dark:text-slate-400">{site.name}</p>
                <h1 className="max-w-2xl text-4xl font-semibold tracking-tight sm:text-6xl">Bienvenido a nuestro sitio web</h1>
                <p className="max-w-xl text-lg text-slate-600 dark:text-slate-300">Conoce nuestras novedades y mantente en contacto con nosotros.</p>
            </section>
        </main>
    );
}
