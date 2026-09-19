import { useForm } from '@inertiajs/react';
import { useRef } from 'react';
import type { FormEvent } from 'react';

export type SettingData = {
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
    const logoInput = useRef<HTMLInputElement>(null);
    const { data, setData, post, processing, errors } = useForm({
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
                if (logoInput.current) {
                    logoInput.current.value = '';
                }
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
            <p className="mt-2 text-sm text-slate-600">Define los colores, la tipografía y el logo de tu sitio.</p>

            <div className="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                    <label htmlFor="logo" className="mb-2 block text-sm font-medium text-slate-700">Logo</label>
                    <input ref={logoInput} id="logo" type="file" accept="image/png,image/jpeg,image/webp" onChange={(event) => setData('logo', event.target.files?.[0] ?? null)} aria-invalid={Boolean(errors.logo)} aria-describedby={errors.logo ? 'logo-help logo-error' : 'logo-help'} className="w-full rounded-lg border border-slate-300 p-3 text-sm" />
                    <p id="logo-help" className="mt-2 text-xs text-slate-500">JPG, PNG o WEBP. Máximo 2 MB. Si no seleccionas otro archivo, conservaremos el logo actual.</p>
                    {errors.logo && <p id="logo-error" role="alert" className="mt-2 text-sm text-red-600">{errors.logo}</p>}
                    {logoUrl && <img src={logoUrl} alt="Logo actual del sitio" className="mt-4 h-16 max-w-full object-contain" />}
                </div>
            </div>

            <button type="submit" disabled={processing} className="mt-6 rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700 disabled:opacity-50">
                {processing ? 'Guardando...' : 'Guardar configuración'}
            </button>
        </form>
    );
}
