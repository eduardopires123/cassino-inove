<div id="usernameModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999; overflow: auto;">
    <div class="modal-content jOkUz" style="position: relative; margin: 15% auto; padding: 20px; max-width: 400px; background-color: #1c1c1e; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <span class="close" id="closeUsernameModal" style="position: absolute; top: 10px; right: 20px; color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h2 style="color: white; text-align: center; margin-bottom: 20px; text-transform: uppercase;">{{ __('header.change_name') }}</h2>
        
        <form id="updateUsernameForm" style="display: flex; flex-direction: column;">
            <div style="margin-bottom: 15px;">
                <label for="username" style="color: white; display: block; margin-bottom: 8px;">{{ __('header.new_name') }}:</label>
                <input type="text" id="username" name="username" style="width: 100%; padding: 10px; background-color: #2c2c2e; border: 1px solid #3c3c3e; border-radius: 5px; color: white;" value="{{ $userName }}" required>
            </div>
            
            <button type="submit" class="_0wldY">
            {{ __('header.save_changes') }}
            </button>
        </form>
    </div>
</div>