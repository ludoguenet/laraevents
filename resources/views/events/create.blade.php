<x-app-layout>
    <section class="text-gray-600 body-font">
        <div class="container px-5 py-24 mx-auto">
            <h1 class="text-xl">Créer votre évènement</h1>
            <form action="{{ route('events.store') }}" id="form" method="post" class="my-4">
                @csrf
                <x-label for="title" value="Titre" />
                <x-input id="title" type="text" name="title" :value="old('title')" />
                <x-label for="content" value="Contenu" />
                <textarea id="content" name="content" :value="old('content')"
                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                ></textarea>
                <x-label for="premium" value="Premium ?" />
                <input type="checkbox" name="premium" id="premium"
                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <x-label for="starts_at" value="Date de démarrage" />
                <x-input id="starts_at" type="date" name="starts_at" />
                <x-label for="ends_at" value="Date de fin" />
                <x-input id="ends_at" type="date" name="ends_at" />
                <x-label for="tags" value="Tags (séparé par une virgule)" />
                <x-input id="tags" type="text" name="tags" class="block" />

                <x-input type="hidden" name="payment_method" id="payment_method" />
                <!-- Stripe Elements Placeholder -->
                <div id="card-element"></div>

                <x-button class="mt-3" id="submit-button">Créer mon événement</x-button>
            </form>
        </div>
    </section>
    @section('extra-js')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe(" {{ env('STRIPE_KEY') }} ");

            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                classes: {
                    base: 'StripeElement bg-white w-1/2 p-2 my-2 rounded-lg'
                }
            });

            cardElement.mount('#card-element');

            const cardButton = document.getElementById('submit-button');
            cardButton.addEventListener('click', async(e) => {
                e.preventDefault();

                const { paymentMethod, error } = await stripe.createPaymentMethod(
                    'card', cardElement
                );

                if (error) {
                    alert(error)
                } else {
                    document.getElementById('payment_method').value = paymentMethod.id;
                }

                document.getElementById('form').submit();
            });
        </script>
    @endsection
</x-app-layout>
