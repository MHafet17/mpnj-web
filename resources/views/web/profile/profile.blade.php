<div class="row">
    <div class="col-md-12">
        @php
            $id = Session::get('id');
        @endphp
        <form action="{{ URL::to('profile/ubah/'.Session::get('role').'/'.Auth::guard('konsumen')->user()->$id) }}" method="post">
            @csrf
            <div class="information_module">
                <a class="toggle_title">
                    <h4>Informasi Pribadi</h4>
                </a>
                <div class="information__set toggle_module">
                    <div class="information_wrapper form--fields">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="text_field" placeholder="nama" value="{{ Auth::guard(Session::get('role'))->user()->nama_lengkap }}">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="text_field" placeholder="email" value="{{ Auth::guard(Session::get('role'))->user()->email }}">
                        </div>

                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="text" id="no_hp" name="no_hp" class="text_field" placeholder="Nomor HP" value="{{ Auth::guard(Session::get('role'))->user()->nomor_hp }}">
                        </div>
                    </div>
                    <!-- end /.information_wrapper -->
                </div>
                <!-- end /.information__set -->
            </div>
            <!-- end /.information_module -->

            <div class="dashboard_setting_btn">
                <button type="submit" name="simpan_profile" class="btn btn--round btn--md">Simpan</button>
            </div>
        </form>
    </div>
    <!-- end /.col-md-12 -->
</div>