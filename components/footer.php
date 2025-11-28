<div class="row mt-5 border-top border-secondary">
    <div class="col-12">
        <div class="row p-3" id="footer-offers"></div>
        <div class="row bg-secondary text-white p-3">
            <div class="col-8 col-md-4 border-end border-white text-center">
                <div class="fs-1" style="font-family: 'gilroy-bold';">papernest</div>
                <div class="p"><br>For inquiries/concerns<br>
                    get in touch with us at<br><br><span class="small">papernestbookstore@gmail.com</span></div>
            </div>
            <div class="col-4 col-md-8">
                <div class="h6 fw-semibold">FOLLOW OUR SOCIAL MEDIA</div>
                <i class="bi bi-facebook fs-2 me-2"></i>
                <i class="bi bi-instagram fs-2 me-2"></i>
                <i class="bi bi-twitter-x fs-2"></i>
            </div>
        </div>
    </div>
</div>
<script>
    const footerData = [
            {
                title: "Free Shipping",
                description: "Shop P799 and above to get your order delivered for free",
                icon : "bi-bag"
            },
            {
                title: "Membership Discounts",
                description: "Card holders enjoy additional 5% off on D-Coded items.",
                icon : "bi-wallet"
            },
            {
                title: "Cash on Delivery",
                description: "Cash on Delivery available for orders above P799.",
                icon : "bi-cash"
            }
        ];

    function displayFooterOffers(){
        const footerRow = document.getElementById('footer-offers');

        footerData.forEach((offer) => {
            footerRow.innerHTML += `
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 col-md-4 p-0 text-center text-md-end"><i class="bi ` + offer.icon + ` h1"></i></div>
                        <div class="col-12 col-md-8">
                            <div class="h6 d-block d-md-none fw-semibold text-center">
                                ` + offer.title + `
                            </div>
                            <div class="h5 d-none d-md-block fw-semibold">
                                ` + offer.title + `
                            </div>
                            <div class="p d-none d-md-block">` + offer.description + `</div>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    displayFooterOffers();
</script>