import { Head, Link, usePage } from '@inertiajs/react';
import AdminAlerts from '../../components/AdminAlerts';
import SettingForm from '../../components/SettingForm';
import type { SettingData } from '../../components/SettingForm';
import BannerCreateForm from '../../components/banners/BannerCreateForm';

type Estado = {
    id: number;
    activo: number;
    inactivo: number;
};

type Props = {
    estados: Estado[];
    logoutUrl: string;
    settingsUrl: string;
    setting: SettingData | null;
    logoUrl: string | null;
};

export default function Admin({ estados, logoutUrl, settingsUrl, setting, logoUrl }: Props) {
    const { site } = usePage<{ site: { name: string } }>().props;
    return (
        <section className="w-full p-6 sm:p-8">
            <Head title={`Administrar - ${site.name}`} />
            <AdminAlerts />
            <Link href={logoutUrl} method="post" as="button" className="mb-6 rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100">
                Cerrar sesión
            </Link>

            <h1 className="text-xl font-semibold">
                Administrar sitio web
            </h1>

            <p className="text-sm text-slate-600">
                Gestiona la configuración y los banners de tu sitio web.
            </p>


            <div className="mt-6 flex flex-col gap-8">
                <SettingForm setting={setting} settingsUrl={settingsUrl} logoUrl={logoUrl} />
                <BannerCreateForm estados={estados} />
            </div>

        </section>
    );
}
