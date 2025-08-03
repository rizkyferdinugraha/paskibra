<x-app-layout>
    <section>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#info_akun" role="tab"
                                aria-controls="home" aria-selected="true">Info Akun</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#info_biodata" role="tab"
                                aria-controls="profile" aria-selected="false" tabindex="-1">Info Biodata</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="info_akun" role="tabpanel"
                            aria-labelledby="home-tab">
                            @include('profile.partial.info_akun')
                        </div>
                        <div class="tab-pane fade" id="info_biodata" role="tabpanel" aria-labelledby="profile-tab">
                            @include('profile.partial.info_biodata')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
