import BannerCreateForm from '../../components/banners/BannerCreateForm'

export default function Admin(): React.JSX.Element {
    return (
        <section className="mx-auto max-w-xl rounded-xl bg-white p-6 shadow-sm">
            <div className="grid gap-1">
                <h1 className="text-xl font-semibold text-slate-950">Crear banner</h1>
                <p className="text-sm text-slate-600">Completa los datos para publicar un nuevo banner.</p>
            </div>

            <div className="mt-6">
                <BannerCreateForm />
            </div>
        </section>
    )
}
