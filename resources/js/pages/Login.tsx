import { Head, Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';

type Props = {
    loginUrl: string;
    homeUrl: string;
};

export default function Login({ loginUrl, homeUrl }: Props) {
    const { data, setData, post, processing, errors, reset } = useForm({ email: '', password: '' });

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        post(loginUrl, { onFinish: () => reset('password') });
    };

    return (
        <main className="flex min-h-[calc(100svh-4rem)] w-full items-center justify-center bg-slate-50 px-4 py-10 text-slate-900 dark:bg-slate-950 dark:text-white">
            <Head title="Iniciar sesión" />

            <div className="flex w-full max-w-sm flex-col gap-6">
                <section aria-labelledby="login-title" className="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-8 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none">
                    <header className="flex flex-col items-center gap-3 text-center">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" className="size-6">
                                <rect x="5" y="10" width="14" height="11" rx="2" />
                                <path strokeLinecap="round" d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3" />
                            </svg>
                        </div>
                        <p className="text-xs font-semibold tracking-widest text-slate-500 dark:text-slate-400">ETS WEBFLEX</p>
                        <div className="flex flex-col gap-2">
                            <h1 id="login-title" className="text-2xl font-semibold tracking-tight">Iniciar sesión</h1>
                            <p className="text-sm leading-6 text-slate-500 dark:text-slate-400">Accede para administrar tu sitio web.</p>
                        </div>
                    </header>

                    <form onSubmit={submit} noValidate className="mt-7 flex flex-col gap-5">
                        <div>
                            <label htmlFor="email" className="mb-2 block text-sm font-medium">Correo electrónico</label>
                            <input
                                id="email" name="email" type="email" autoComplete="username" autoFocus
                                placeholder="nombre@empresa.com"
                                value={data.email} onChange={(event) => setData('email', event.target.value)}
                                aria-required="true" aria-invalid={Boolean(errors.email)} aria-describedby={errors.email ? 'email-error' : undefined}
                                className="min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm transition-colors placeholder:text-slate-400 focus:border-slate-600 focus:outline-2 focus:outline-offset-2 focus:outline-slate-300 aria-invalid:border-red-600 dark:border-slate-600 dark:bg-slate-950 dark:focus:border-slate-400 dark:focus:outline-slate-600 dark:aria-invalid:border-red-400"
                            />
                            {errors.email && <p id="email-error" role="alert" className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.email}</p>}
                        </div>

                        <div>
                            <label htmlFor="password" className="mb-2 block text-sm font-medium">Contraseña</label>
                            <input
                                id="password" name="password" type="password" autoComplete="current-password"
                                placeholder="Ingresa tu contraseña"
                                value={data.password} onChange={(event) => setData('password', event.target.value)}
                                aria-required="true" aria-invalid={Boolean(errors.password)} aria-describedby={errors.password ? 'password-error' : undefined}
                                className="min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm transition-colors placeholder:text-slate-400 focus:border-slate-600 focus:outline-2 focus:outline-offset-2 focus:outline-slate-300 aria-invalid:border-red-600 dark:border-slate-600 dark:bg-slate-950 dark:focus:border-slate-400 dark:focus:outline-slate-600 dark:aria-invalid:border-red-400"
                            />
                            {errors.password && <p id="password-error" role="alert" className="mt-2 text-sm text-red-600 dark:text-red-400">{errors.password}</p>}
                        </div>

                        <button type="submit" disabled={processing} className="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-500 disabled:cursor-wait disabled:opacity-60 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                            {processing ? 'Ingresando...' : 'Iniciar sesión'}
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="size-4">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>
                        </button>
                    </form>
                </section>

                <Link href={homeUrl} className="inline-flex min-h-11 items-center justify-center gap-2 self-center rounded-lg px-3 text-sm text-slate-600 transition-colors hover:text-slate-950 focus-visible:outline-2 focus-visible:outline-offset-2 dark:text-slate-400 dark:hover:text-white">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" className="size-4">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19 12H5m6-6-6 6 6 6" />
                    </svg>
                    Volver al inicio
                </Link>
            </div>
        </main>
    );
}
