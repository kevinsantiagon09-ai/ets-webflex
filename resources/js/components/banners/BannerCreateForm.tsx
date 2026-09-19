import { FormEvent } from 'react';
import { useForm } from '@inertiajs/react';

type Estado = {
    id: number;
    nombre?: string;
};

type Props = {
    estados?: Estado[];
};

export default function BannerCreateForm({
    estados = [],
}: Props) {

    const {
        data,
        setData,
        post,
        processing,
        errors,
        reset,
    } = useForm({
        titulo: '',
        estado_id: '',
        image: null as File | null,
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();

        post('/admin/banners', {
            forceFormData: true,

            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <form
            onSubmit={submit}
            className="space-y-6 rounded-xl bg-white p-6 shadow"
        >

            {/* Título */}
            <div>
                <label
                    htmlFor="titulo"
                    className="mb-2 block text-sm font-medium text-gray-700"
                >
                    Título del banner
                </label>

                <input
                    id="titulo"
                    type="text"
                    value={data.titulo}
                    onChange={(e) =>
                        setData('titulo', e.target.value)
                    }
                    className="w-full rounded-lg border border-gray-300 px-4 py-2"
                    placeholder="Ej: Bienvenido a WebFlex"
                />

                {errors.titulo && (
                    <p className="mt-1 text-sm text-red-600">
                        {errors.titulo}
                    </p>
                )}
            </div>

            {/* Estado */}
            <div>
                <label
                    htmlFor="estado_id"
                    className="mb-2 block text-sm font-medium text-gray-700"
                >
                    Estado
                </label>

                <select
                    id="estado_id"
                    value={data.estado_id}
                    onChange={(e) =>
                        setData('estado_id', e.target.value)
                    }
                    className="w-full rounded-lg border border-gray-300 px-4 py-2"
                >
                    <option value="">
                        Selecciona un estado
                    </option>

                    {estados.map((estado) => (
                        <option
                            key={estado.id}
                            value={estado.id}
                        >
                            {estado.nombre ?? `Estado ${estado.id}`}
                        </option>
                    ))}
                </select>

                {errors.estado_id && (
                    <p className="mt-1 text-sm text-red-600">
                        {errors.estado_id}
                    </p>
                )}
            </div>

            {/* Imagen */}
            <div>
                <label
                    htmlFor="image"
                    className="mb-2 block text-sm font-medium text-gray-700"
                >
                    Imagen del banner
                </label>

                <input
                    id="image"
                    type="file"
                    accept="image/*"
                    onChange={(e) =>
                        setData(
                            'image',
                            e.target.files?.[0] ?? null
                        )
                    }
                    className="w-full rounded-lg border border-gray-300 px-4 py-2"
                />

                {errors.image && (
                    <p className="mt-1 text-sm text-red-600">
                        {errors.image}
                    </p>
                )}
            </div>

            <button
                type="submit"
                disabled={processing}
                className="rounded-lg bg-slate-900 px-5 py-2 text-white disabled:opacity-50"
            >
                {processing
                    ? 'Guardando...'
                    : 'Guardar banner'}
            </button>

        </form>
    );
}