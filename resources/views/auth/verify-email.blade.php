<x-layout title="Email Verify">
    <div class="border h-screen flex justify-center items-center">
        <div class="text-center" style="width: 20rem;">
            <h1 class="text-xl mb-5">Email verification</h1>
            <p class="mb-2">Please check your Email</p>
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded">Resend</button>
            </form>
        </div>
    </div>
</x-layout>