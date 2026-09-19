import BannerCreateForm from '../../components/banners/BannerCreateForm';

export default function Admin() {
    return (
        <section className="mx-auto max-w-xl p-6">

            <h1 className="text-xl font-semibold">
                Crear banner
            </h1>

            <p className="text-sm text-slate-600">
                Completa los datos para publicar un nuevo banner.
            </p>

            <div className="mt-6">
                <BannerCreateForm />
            </div>

        </section>
    );
}