import { useForm } from '@inertiajs/react';
import ImageDropzone from './ImageDropzone';
import type { FormEvent } from 'react';

export type SettingData = {
    site_name: string | null;
    primary_color: string | null;
    text_color: string | null;
    button_color: string | null;
    font_family: string | null;
};

type Props = {
    setting: SettingData | null;
    settingsUrl: string;
    logoUrl: string | null;
};

export default function SettingForm({ setting, settingsUrl, logoUrl }: Props) {
    const { data, setData, post, processing, errors, clearErrors } = useForm({
        site_name: setting?.site_name ?? 'ETS WebFlex',
        primary_color: setting?.primary_color ?? '#0f172a',
        text_color: setting?.text_color ?? '#334155',
        button_color: setting?.button_color ?? '#0f172a',
        font_family: setting?.font_family ?? 'Instrument Sans',
        logo: null as File | null,
    });

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        post(settingsUrl, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                setData('logo', null);
            },
        });
    };

    const colors = [
        ['primary_color', 'Color principal'],
        ['text_color', 'Color del texto'],
        ['button_color', 'Color de los botones'],
    ] as const;

    return (
        <form onSubmit={submit} noValidate className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 className="text-xl font-semibold text-slate-900">Configuración del sitio</h2>
            <p className="mt-2 text-sm text-slate-600">Define el nombre, el logo y la apariencia de tu sitio.</p>

            <div className="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div className="sm:col-span-2 lg:col-span-3">
                    <label htmlFor="site_name" className="mb-2 block text-sm font-medium text-slate-700">Nombre del sitio web</label>
                    <input id="site_name" type="text" value={data.site_name} onChange={(event) => setData('site_name', event.target.value)} aria-invalid={Boolean(errors.site_name)} aria-describedby={errors.site_name ? 'site-name-error' : undefined} className="w-full rounded-lg border border-slate-300 px-3 py-3" />
                    {errors.site_name && <p id="site-name-error" role="alert" className="mt-2 text-sm text-red-600">{errors.site_name}</p>}
                </div>
                {colors.map(([field, label]) => (
                    <div key={field}>
                        <label htmlFor={field} className="mb-2 block text-sm font-medium text-slate-700">{label}</label>
                        <div className="flex items-center gap-3">
                            <input id={field} type="color" value={data[field]} onChange={(event) => setData(field, event.target.value)} aria-invalid={Boolean(errors[field])} aria-describedby={errors[field] ? `${field}-error` : undefined} className="h-11 w-16 cursor-pointer rounded-lg border border-slate-300 p-1" />
                            <span className="text-sm text-slate-600">{data[field]}</span>
                        </div>
                        {errors[field] && <p id={`${field}-error`} role="alert" className="mt-2 text-sm text-red-600">{errors[field]}</p>}
                    </div>
                ))}

                <div>
                    <label htmlFor="font_family" className="mb-2 block text-sm font-medium text-slate-700">Fuente</label>
                    <select id="font_family" value={data.font_family} onChange={(event) => setData('font_family', event.target.value)} aria-invalid={Boolean(errors.font_family)} aria-describedby={errors.font_family ? 'font-error' : undefined} className="w-full rounded-lg border border-slate-300 bg-white px-3 py-3">
                        {['Instrument Sans', 'Arial', 'Verdana', 'Georgia'].map((font) => <option key={font} value={font}>{font}</option>)}
                    </select>
                    {errors.font_family && <p id="font-error" role="alert" className="mt-2 text-sm text-red-600">{errors.font_family}</p>}
                </div>

                <div className="sm:col-span-2">
                    <ImageDropzone id="logo" label="Logo del sitio" value={data.logo} onChange={(file) => { setData('logo', file); clearErrors('logo'); }} error={errors.logo} disabled={processing} existingUrl={logoUrl} />
                    <p className="mt-2 text-xs text-slate-500">Si no seleccionas otro archivo, conservaremos el logo actual.</p>
                </div>
            </div>

            <button type="submit" disabled={processing} className="mt-6 rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700 disabled:opacity-50">
                {processing ? 'Guardando...' : 'Guardar configuración'}
            </button>
        </form>
    );
}
