<footer class="px-5 py-10 mt-auto text-white bg-slate-800 bottom-0 w-full" id="footer">
    <div class="flex flex-col md:flex-row items-start justify-center gap-3">
        <div class="w-full xl:w-1/3 self-center px-4">
            <p class="font-extrabold text-start text-base">Sangnila LMS WebApp {{ trans('strings.version') }}</p>
            <p class="text-start mt-2">Copyright &copy 2025 - Sangnila Interactive Media and Technology</p>
            <a href="{{ route('guest.privacy-policy') }}" class="hover:underline hover:font-semibold hover:bg-rounded-lg block mt-8">Privacy Policy</a>
        </div>
        <div class="w-full md:w-1/3 xl:w-1/4 flex flex-col items-start px-4">
            <span class="font-bold mb-2 mt-6 xl:mt-0">Find Us</span>
            <a href="https://maps.app.goo.gl/Did4dZueYwVNtSM9A" class="hover:underline hover:font-semibold" target="_blank">Paskal Hyper Square B70, Jl.Pasir Kaliki No.23, Kec. Cicendo, Kota Bandung</a>
            <span class="font-bold mb-2 mt-4">Contact Us</span>
            <a href="https://wa.me/6285693257411" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-telephone"></i> +62 856-9325-7411</a>
            <a href="mailto:admin@sangnilaindonesia.com" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-envelope"></i> admin@sangnilaindonesia.com</a>
        </div>
        <div class="flex flex-row w-full xl:w-1/3 px-4">
            <div class="w-1/3 md:w-1/2 flex flex-col items-start mt-6 xl:mt-0">
                <span class="font-bold mb-2">Social Media</span>
                <a href="https://www.instagram.com/sangnila.ac/" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-instagram"></i> Instagram</a>
                <a href="https://www.facebook.com/sangnila.artsacademy" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-facebook"></i> Facebook</a>
                <a href="https://www.tiktok.com/@sangnilaartsacademy" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-tiktok"></i> Tiktok</a>
                <a href="https://www.youtube.com/@sangnilaartsacademy1709" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-youtube"></i> Youtube</a>
                <a href="https://www.linkedin.com/company/pt-sangnila-interaktif-media-dan-teknologi/" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-linkedin"></i> LinkedIn</a>
            </div>
            <div class="w-1/3 xl:w-1/2 flex flex-col items-start mt-6 xl:mt-0">
                <span class="font-bold mb-2">Sangnila WebApps</span>
                <a href="https://sangnilaindonesia.com/" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-globe2"></i> Main Website</a>
                <a href="https://register.sangnilaindonesia.com/admin-login/" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-globe2"></i> Registration</a>
                @if(Auth::check() && Auth::user()->role->id != 3)
                    <a href="https://finance.sangnilaindonesia.com/" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-globe2"></i> Finance</a>
                    <a href="https://psikotest.sangnilaindonesia.com/" class="hover:underline hover:font-semibold hover:bg-rounded-lg" target="_blank"><i class="bi bi-globe2"></i> Online Psychotest</a>
                @endif
            </div>
        </div>
    </div>

</footer>
