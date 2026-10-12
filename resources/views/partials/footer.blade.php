<link rel="stylesheet" href="{{ asset('assets/css/fontAwesome5Pro.css') }}">
<style>
	.store-footer { background:#21371f; border-radius:30px 30px 0 0; color:#f5f6ef; margin:28px 24px 0; overflow:hidden; }
	.store-footer__inner { margin:0 auto; max-width:1560px; padding:76px 7.5% 28px; }
	.store-footer__main { display:grid; gap:60px; grid-template-columns:minmax(250px,1.2fr) minmax(160px,.65fr) minmax(320px,1fr); }
	.store-footer__brand img { display:block; filter:invert(1) grayscale(1) brightness(4); height:auto; mix-blend-mode:screen; width:190px; }
	.store-footer__brand p { color:#e2e7dc; font-size:14px; line-height:1.55; margin:62px 0 0; max-width:390px; }
	.store-footer h2 { color:#fff; font-size:28px; font-weight:400; line-height:1.2; margin:0 0 24px; }
	.store-footer__links { display:grid; gap:14px; }
	.store-footer a { color:inherit; text-decoration:none; }
	.store-footer__links a { font-size:15px; width:fit-content; }
	.store-footer a:hover { color:#d2bd3f; }
	.store-footer__address { font-size:15px; font-style:normal; line-height:1.65; margin:0 0 17px; }
	.store-footer__contact-link { align-items:center; display:flex; gap:11px; font-size:15px; margin-top:13px; width:fit-content; }
	.store-footer__contact-link i { color:#e2e7dc; font-size:17px; text-align:center; width:19px; }
	.store-footer__bottom { align-items:center; border-top:1px solid rgba(0,0,0,.22); display:flex; gap:24px; justify-content:space-between; margin-top:78px; padding-top:28px; }
	.store-footer__bottom p { color:#e2e7dc; font-size:14px; line-height:1.5; margin:0; }
	@media(max-width:900px) {
		.store-footer__inner { padding-left:6%; padding-right:6%; }
		.store-footer__main { gap:42px 32px; grid-template-columns:minmax(0,1fr) minmax(0,1fr); }
		.store-footer__brand { grid-column:1 / -1; }
		.store-footer__brand p { margin-top:28px; }
		.store-footer__bottom { margin-top:52px; }
	}
	@media(max-width:600px) {
		.store-footer { border-radius:22px 22px 0 0; margin:22px 12px 0; }
		.store-footer__inner { padding:42px 24px 24px; }
		.store-footer__main { gap:34px; grid-template-columns:1fr; }
		.store-footer__brand { grid-column:auto; }
		.store-footer__brand img { width:165px; }
		.store-footer__brand p { font-size:13px; margin-top:22px; }
		.store-footer h2 { font-size:24px; margin-bottom:16px; }
		.store-footer__links { gap:12px; }
		.store-footer__links a,.store-footer__address,.store-footer__contact-link { font-size:14px; }
		.store-footer__bottom { align-items:flex-start; margin-top:38px; padding-top:20px; }
		.store-footer__bottom p { font-size:12px; }
	}
</style>
<footer class="store-footer">
	<div class="store-footer__inner">
		<div class="store-footer__main">
			<div class="store-footer__brand">
				<a href="{{ route('home') }}" aria-label="The Lodge Maribaya, beranda">
					<img src="{{ asset('assets/img/logo/logothelodge.png') }}" alt="The Lodge Maribaya">
				</a>
				<p>The Lodge Maribaya is managed by PT Alam Kharisma Indonesia.</p>
			</div>
			<nav class="store-footer__menu" aria-label="Quick menu">
				<h2>Quick Menu</h2>
				<div class="store-footer__links">
					<a href="{{ route('home') }}">Home</a>
					<a href="{{ route('shop') }}">All Products</a>
					<a href="{{ route('new-arrivals') }}">New Arrivals</a>
				</div>
			</nav>
			<div class="store-footer__contact">
				<h2>Contact Info</h2>
				<address class="store-footer__address"><i class="fal fa-map-marker-alt" aria-hidden="true"></i> Jalan Maribaya No. 149/252 RT. 03 / RW. 15, Babakan, Gentong, Cibodas, Kec. Lembang, Kabupaten Bandung Barat, Jawa Barat 40391</address>
				<a class="store-footer__contact-link" href="tel:+628112349956"><i class="fal fa-phone-alt" aria-hidden="true"></i><span>+62 811-2349-956</span></a>
				<a class="store-footer__contact-link" href="mailto:info@thelodgegroup.id"><i class="fal fa-envelope-open-text" aria-hidden="true"></i><span>info@thelodgegroup.id</span></a>
			</div>
		</div>
		<div class="store-footer__bottom">
			<p>Copyright &copy; The Lodge Group {{ now()->year }}. All rights reserved.</p>
		</div>
	</div>
</footer>
