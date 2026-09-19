import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useDropzone } from 'react-dropzone';
import type { Site } from '../types/site';

type Props = {
    id: string;
    label: string;
    value: File | null;
    onChange: (file: File | null) => void;
    error?: string;
    disabled?: boolean;
    existingUrl?: string | null;
};

export default function ImageDropzone({ id, label, value, onChange, error, disabled = false, existingUrl }: Props) {
    const { site } = usePage<{ site: Site }>().props;
    const [preview, setPreview] = useState<string | null>(null);
    const [rejection, setRejection] = useState<string | null>(null);

    useEffect(() => {
        if (!value) {
            setPreview(null);
            return;
        }
        const url = URL.createObjectURL(value);
        setPreview(url);
        return () => URL.revokeObjectURL(url);
    }, [value]);

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        accept: { 'image/jpeg': ['.jpg', '.jpeg'], 'image/png': ['.png'], 'image/webp': ['.webp'] },
        multiple: false,
        maxSize: 2 * 1024 * 1024,
        disabled,
        onDrop: (acceptedFiles, rejectedFiles) => {
            if (rejectedFiles.length) {
                onChange(null);
                const codes = rejectedFiles.flatMap((file) => file.errors.map((issue) => issue.code));
                setRejection(codes.includes('too-many-files') ? 'Selecciona solo una imagen.' : codes.includes('file-too-large') ? 'La imagen no puede superar los 2 MB.' : 'Selecciona una imagen JPG, PNG o WEBP.');
                return;
            }
            setRejection(null);
            onChange(acceptedFiles[0] ?? null);
        },
    });
    const message = rejection || error;
    const imageUrl = preview || existingUrl;

    return (
        <div>
            <p id={`${id}-label`} className="mb-2 text-sm font-medium text-slate-700">{label}</p>
            <div
                {...getRootProps({
                    role: 'button',
                    'aria-labelledby': `${id}-label`,
                    'aria-describedby': `${id}-help${message ? ` ${id}-error` : ''}`,
                    'aria-disabled': disabled,
                    'aria-invalid': Boolean(message),
                })}
                className={`flex min-h-40 flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed p-5 text-center transition-colors focus-visible:outline-2 focus-visible:outline-offset-4 ${disabled ? 'cursor-wait opacity-60' : 'cursor-pointer hover:bg-slate-50'} ${isDragActive ? 'bg-slate-100' : 'bg-white'}`}
                style={{ borderColor: message ? '#dc2626' : site.primaryColor, color: site.textColor }}
            >
                <input {...getInputProps({ id, name: id, 'aria-labelledby': `${id}-label` })} />
                {imageUrl ? <img src={imageUrl} alt={`Vista previa: ${label}`} className="h-24 max-w-full rounded-lg object-contain" /> : (
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" className="size-8">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 16V3m-5 5 5-5 5 5M4 16v4a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-4" />
                    </svg>
                )}
                <span className="text-sm font-medium">{isDragActive ? 'Suelta la imagen aquí' : 'Arrastra una imagen o haz clic para seleccionarla'}</span>
                {value && <span className="max-w-full break-all text-xs">{value.name}</span>}
            </div>
            <p id={`${id}-help`} className="mt-2 text-xs text-slate-500">JPG, PNG o WEBP · Máximo 2 MB · Una imagen</p>
            {message && <p id={`${id}-error`} role="alert" className="mt-2 text-sm text-red-600">{message}</p>}
            {value && <button type="button" disabled={disabled} onClick={() => { setRejection(null); onChange(null); }} className="mt-2 rounded px-2 py-1 text-sm underline underline-offset-4">Quitar selección</button>}
        </div>
    );
}
