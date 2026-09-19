import BannerCreateForm from '../../components/banners/BannerCreateForm';

export default function Create() {
    return (
        <div className="mx-auto max-w-2xl p-6">

            <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-900">
                    Crear Banner
                </h1>

                <p className="mt-1 text-sm text-gray-600">
                    Configura el banner que aparecerá en tu página web.
                </p>
            </div>

            <BannerCreateForm />

        </div>
    );
}