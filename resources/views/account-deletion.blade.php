<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression de compte - LTMO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-700 antialiased">

    <nav class="border-b border-slate-200">
        <div class="max-w-2xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="text-sm font-semibold tracking-tight text-slate-900">LTMO</a>
            <a href="/" class="text-sm text-slate-500 hover:text-slate-900">← Accueil</a>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-6 py-16">
        <p class="text-sm font-medium text-indigo-600 mb-3">Vos données</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Suppression de compte</h1>
        <p class="mt-3 text-slate-500 leading-relaxed">
            Vous pouvez demander la suppression définitive de votre compte LTMO et de
            l'ensemble des données associées (rendez-vous, médicaments, historique de
            prise, informations de couple). Indiquez ci-dessous l'adresse email de votre
            compte : votre demande nous sera transmise et traitée manuellement.
        </p>

        @if(session('success'))
        <div class="mt-8 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
            Votre demande a bien été envoyée. Elle sera traitée sous quelques jours ouvrés.
        </div>
        @endif

        <form method="POST" action="{{ route('account-deletion.submit') }}" class="mt-10 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">Adresse email du compte</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    placeholder="vous@exemple.fr">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-slate-900">Message <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <textarea name="message" id="message" rows="4"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    placeholder="Précisions éventuelles">{{ old('message') }}</textarea>
                @error('message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="inline-flex items-center rounded-md bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                Envoyer la demande de suppression
            </button>
        </form>

        <p class="mt-10 text-sm text-slate-400 leading-relaxed">
            Pour en savoir plus sur les données que nous traitons et vos droits, consultez notre
            <a href="{{ route('privacy') }}" class="text-indigo-600 hover:underline">politique de confidentialité</a>.
        </p>
    </main>

    <footer class="border-t border-slate-200 mt-8">
        <div class="max-w-2xl mx-auto px-6 py-8 flex items-center justify-between text-sm text-slate-400">
            <p>&copy; {{ now()->format('Y') }} LTMO</p>
            <div class="flex gap-6">
                <a href="/" class="hover:text-slate-600">Accueil</a>
                <a href="{{ route('privacy') }}" class="hover:text-slate-600">Confidentialité</a>
                <a href="{{ route('account-deletion') }}" class="text-slate-600 font-medium">Suppression de compte</a>
            </div>
        </div>
    </footer>

</body>
</html>
