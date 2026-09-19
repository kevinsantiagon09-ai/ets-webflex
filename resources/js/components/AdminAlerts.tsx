import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import Swal from 'sweetalert2';
import type { Site } from '../types/site';

type Props = {
    site: Site;
    flash: { success: string | null; error: string | null; info: string | null };
    errors: Record<string, string>;
};

export default function AdminAlerts() {
    const { site, flash, errors } = usePage<Props>().props;

    useEffect(() => {
        const hasErrors = Object.keys(errors).length > 0;
        const message = flash.error || (hasErrors ? 'Revisa los campos indicados en el formulario.' : flash.success || flash.info);
        if (!message) {
            return;
        }

        const icon = flash.error || hasErrors ? 'error' : flash.success ? 'success' : 'info';
        void Swal.fire({
            titleText: icon === 'error' ? 'No se pudo guardar' : icon === 'success' ? 'Guardado correctamente' : 'Información',
            text: message,
            icon,
            iconColor: site.primaryColor,
            color: site.textColor,
            background: '#ffffff',
            confirmButtonColor: site.buttonColor,
            confirmButtonText: 'Entendido',
            didOpen: () => {
                const button = Swal.getConfirmButton();
                const rgb = site.buttonColor.replace('#', '').match(/.{2}/g)?.map((part) => parseInt(part, 16)) ?? [15, 23, 42];
                const brightness = (rgb[0] * 299 + rgb[1] * 587 + rgb[2] * 114) / 1000;
                if (button) {
                    button.style.color = brightness > 150 ? '#0f172a' : '#ffffff';
                }
            },
        });
    }, [flash, errors, site]);

    return null;
}
