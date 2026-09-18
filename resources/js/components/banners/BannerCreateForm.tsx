import { useForm } from '@inertiajs/react'
import type { FormEvent } from 'react'
import type { BannerFormData } from '../../types/banner'

const initialValues: BannerFormData = {
    estado_id: '',
    image_path: '',
    titulo: '',
}

export default function BannerCreateForm(): React.JSX.Element {
    const form = useForm<BannerFormData>(initialValues)

    function submit(event: FormEvent<HTMLFormElement>): void {
        event.preventDefault()

        form.post('/admin/banners', {
            onSuccess: () => form.reset(),
        })
    }

    return (
        <form className="grid gap-5" noValidate onSubmit={submit}>
            <div className="grid gap-2">
                <label className="text-sm font-medium text-slate-700" htmlFor="titulo">Título</label>
                <input aria-describedby={form.errors.titulo ? 'titulo-error' : undefined} aria-invalid={Boolean(form.errors.titulo)} className="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-600/20" id="titulo" name="titulo" onChange={(event) => form.setData('titulo', event.target.value)} value={form.data.titulo} />
                {form.errors.titulo && <p className="text-sm text-red-600" id="titulo-error">{form.errors.titulo}</p>}
            </div>

            <div className="grid gap-2">
                <label className="text-sm font-medium text-slate-700" htmlFor="image_path">Ruta de imagen</label>
                <input aria-describedby={form.errors.image_path ? 'image-path-error' : undefined} aria-invalid={Boolean(form.errors.image_path)} className="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-600/20" id="image_path" name="image_path" onChange={(event) => form.setData('image_path', event.target.value)} value={form.data.image_path} />
                {form.errors.image_path && <p className="text-sm text-red-600" id="image-path-error">{form.errors.image_path}</p>}
            </div>

            <div className="grid gap-2">
                <label className="text-sm font-medium text-slate-700" htmlFor="estado_id">ID del estado</label>
                <input aria-describedby={form.errors.estado_id ? 'estado-id-error' : undefined} aria-invalid={Boolean(form.errors.estado_id)} className="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-sky-600 focus:ring-2 focus:ring-sky-600/20" id="estado_id" min="1" name="estado_id" onChange={(event) => form.setData('estado_id', event.target.value)} type="number" value={form.data.estado_id} />
                {form.errors.estado_id && <p className="text-sm text-red-600" id="estado-id-error">{form.errors.estado_id}</p>}
            </div>

            <button className="w-fit rounded-md bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800 disabled:cursor-not-allowed disabled:bg-slate-400" disabled={form.processing} type="submit">
                {form.processing ? 'Guardando…' : 'Crear banner'}
            </button>
        </form>
    )
}
