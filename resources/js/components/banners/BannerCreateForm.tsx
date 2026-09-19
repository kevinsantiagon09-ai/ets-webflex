import { FormEvent, useEffect, useState } from 'react';
import { useForm } from '@inertiajs/react';
import ImageDropzone from '../ImageDropzone';

type Estado = {
    id: number;
    activo: number;
    inactivo: number;
};

type Props = {
    estados?: Estado[];
};

export default function BannerCreateForm({
    estados = [],
}: Props) {
    const [preview, setPreview] = useState<string | null>(null);

    const {
        data,
        setData,
        post,
        processing,
        errors,
        clearErrors,
        reset,
    } = useForm({
        titulo: '',
        estado_id: '',
        image: null as File | null,
    });

    /*
     * Crear vista previa de la imagen seleccionada.
     */
    useEffect(() => {
        if (!data.image) {
            setPreview(null);
            return;
        }

        const imageUrl = URL.createObjectURL(data.image);

        setPreview(imageUrl);

        return () => {
            URL.revokeObjectURL(imageUrl);
        };
    }, [data.image]);

    /*
     * Enviar formulario a Laravel.
     */
    const submit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post('/admin/banners', {
            forceFormData: true,

            preserveScroll: true,

            onSuccess: () => {
                reset();
                setPreview(null);
            },
        });
    };

    return (
        <div className="grid gap-8 lg:grid-cols-2">

            {/* ========================= */}
            {/* FORMULARIO */}
            {/* ========================= */}

            <form
                onSubmit={submit}
                encType="multipart/form-data"
                className="rounded-2xl bg-white p-6 shadow"
            >
                <h2 className="mb-6 text-xl font-bold text-slate-900">
                    Configuración del banner
                </h2>

                {/* TÍTULO */}
                <div className="mb-5">
                    <label
                        htmlFor="titulo"
                        className="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Título
                    </label>

                    <input
                        id="titulo"
                        name="titulo"
                        type="text"
                        value={data.titulo}
                        onChange={(e) =>
                            setData('titulo', e.target.value)
                        }
                        placeholder="Ej: Impulsa tu negocio con tecnología"
                        className="
                            w-full
                            rounded-lg
                            border
                            border-slate-300
                            px-4
                            py-3
                            outline-none
                            transition
                            focus:border-slate-900
                            focus:ring-1
                            focus:ring-slate-900
                        "
                    />

                    {errors.titulo && (
                        <p className="mt-1 text-sm text-red-600">
                            {errors.titulo}
                        </p>
                    )}
                </div>

                {/* ESTADO */}
                <div className="mb-5">
                    <label
                        htmlFor="estado_id"
                        className="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Estado
                    </label>

                    <select
                        id="estado_id"
                        name="estado_id"
                        value={data.estado_id}
                        onChange={(e) =>
                            setData('estado_id', e.target.value)
                        }
                        className="
                            w-full
                            rounded-lg
                            border
                            border-slate-300
                            bg-white
                            px-4
                            py-3
                            outline-none
                            focus:border-slate-900
                            focus:ring-1
                            focus:ring-slate-900
                        "
                    >
                        <option value="">
                            Seleccione un estado
                        </option>

                        {estados.map((estado) => (
                            <option
                                key={estado.id}
                                value={estado.id}
                            >
                                {estado.activo === 1
                                    ? 'Activo'
                                    : estado.inactivo === 1
                                        ? 'Inactivo'
                                        : `Estado ${estado.id}`}
                            </option>
                        ))}
                    </select>

                    {errors.estado_id && (
                        <p className="mt-1 text-sm text-red-600">
                            {errors.estado_id}
                        </p>
                    )}

                    {estados.length === 0 && (
                        <p className="mt-2 text-sm text-amber-600">
                            No hay estados registrados en la base de datos.
                        </p>
                    )}
                </div>

                {/* IMAGEN */}
                <div className="mb-6">
                    <ImageDropzone id="image" label="Imagen del banner" value={data.image} onChange={(file) => { setData('image', file); clearErrors('image'); }} error={errors.image} disabled={processing} />
                </div>

                {/* BOTÓN */}
                <button
                    type="submit"
                    disabled={processing}
                    className="
                        w-full
                        rounded-lg
                        bg-slate-950
                        px-5
                        py-3
                        font-semibold
                        text-white
                        transition
                        hover:bg-slate-800
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                    "
                >
                    {processing
                        ? 'Guardando...'
                        : 'Guardar banner'}
                </button>
            </form>

            {/* ========================= */}
            {/* VISTA PREVIA */}
            {/* ========================= */}

            <div>
                <h2 className="mb-4 text-xl font-bold text-slate-900">
                    Vista previa
                </h2>

                <div
                    className="
                        relative
                        min-h-[420px]
                        overflow-hidden
                        rounded-3xl
                        bg-slate-950
                        shadow-xl
                    "
                >
                    {/* IMAGEN */}
                    {preview && (
                        <img
                            src={preview}
                            alt="Vista previa del banner"
                            className="
                                absolute
                                inset-0
                                h-full
                                w-full
                                object-cover
                            "
                        />
                    )}

                    {/* OVERLAY */}
                    <div
                        className="
                            absolute
                            inset-0
                            bg-gradient-to-r
                            from-black/90
                            via-black/60
                            to-black/20
                        "
                    />

                    {/* CONTENIDO */}
                    <div
                        className="
                            relative
                            flex
                            min-h-[420px]
                            items-center
                            p-10
                        "
                    >
                        <div className="max-w-md">

                            <span
                                className="
                                    mb-4
                                    inline-block
                                    rounded-full
                                    bg-white/10
                                    px-4
                                    py-2
                                    text-sm
                                    text-white
                                    backdrop-blur
                                "
                            >
                                WebFlex
                            </span>

                            <h1
                                className="
                                    text-4xl
                                    font-bold
                                    leading-tight
                                    text-white
                                "
                            >
                                {data.titulo ||
                                    'Título de tu banner'}
                            </h1>

                            <p className="mt-4 text-sm text-slate-300">
                                Vista previa del banner configurable.
                            </p>

                            {/* ESTADO EN PREVIEW */}
                            {data.estado_id && (
                                <div className="mt-5">
                                    <span
                                        className="
                                            rounded-full
                                            bg-white/10
                                            px-3
                                            py-1
                                            text-xs
                                            text-white
                                        "
                                    >
                                        {estados.find(
                                            (estado) =>
                                                estado.id.toString() ===
                                                data.estado_id
                                        )?.activo === 1
                                            ? 'Activo'
                                            : 'Inactivo'}
                                    </span>
                                </div>
                            )}

                        </div>
                    </div>
                </div>
            </div>

        </div>
    );
}