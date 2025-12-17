    <div class="_6NoZq" data-v-owner="3076" style="--236d1da4: 55px;">
        <form class="s-lPk" method="POST" action="{{ route('user.update.phone') }}">
            @csrf
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Phone</button>
        </form>
    </div> 