import BannerCreateForm
    from '../../components/banners/BannerCreateForm';

type Estado = {
    id: number;
    activo: number;
    inactivo: number;
};

type Props = {
    estados: Estado[];
};

export default function Create({ estados }: Props) {
    return (
        <main className="min-h-screen bg-slate-100 p-8">
            <div className="mx-auto max-w-7xl">

                <h1 className="text-3xl font-bold">
                    Crear Banner
                </h1>

                <BannerCreateForm estados={estados} />

            </div>
        </main>
    );
}