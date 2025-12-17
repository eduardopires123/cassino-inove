<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSettings;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class FooterSettingsController extends Controller
{
    /**
     * Display a form to edit footer settings.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $footerSettings = FooterSettings::getSettings();
        $socialLinks = SocialLink::first() ?: new SocialLink();
        
        return view('admin.footer-settings.edit', compact('footerSettings', 'socialLinks'));
    }

    /**
     * Update the footer settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'footer_text' => 'required|string',
            'footer_subtext' => 'nullable|string',
            'contact_button_url' => 'nullable|string|max:255',
            'show_autorizado_cassino' => 'boolean',
            'show_social_links' => 'boolean',
            'topbar_text' => 'required|string|max:255',
            'topbar_button_text' => 'required|string|max:100',
            'topbar_button_url' => 'required|string|max:255',
            'show_topbar' => 'boolean',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'show_instagram' => 'boolean',
            'show_facebook' => 'boolean',
            'show_telegram' => 'boolean',
            'show_whatsapp' => 'boolean',
        ]);

        // Update footer settings
        $footerSettings = FooterSettings::getSettings();
        $footerSettings->update([
            'footer_text' => $request->footer_text,
            'footer_subtext' => $request->footer_subtext,
            'contact_button_url' => $request->contact_button_url,
            'show_autorizado_cassino' => $request->has('show_autorizado_cassino'),
            'show_social_links' => $request->has('show_social_links'),
            'topbar_text' => $request->topbar_text,
            'topbar_button_text' => $request->topbar_button_text,
            'topbar_button_url' => $request->topbar_button_url,
            'show_topbar' => $request->has('show_topbar'),
        ]);

        // Update social links
        $socialLinks = SocialLink::first();
        if ($socialLinks) {
            $socialLinks->update([
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'telegram' => $request->telegram,
                'whatsapp' => $request->whatsapp,
                'show_instagram' => $request->has('show_instagram'),
                'show_facebook' => $request->has('show_facebook'),
                'show_telegram' => $request->has('show_telegram'),
                'show_whatsapp' => $request->has('show_whatsapp'),
            ]);
        } else {
            SocialLink::create([
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'telegram' => $request->telegram,
                'whatsapp' => $request->whatsapp,
                'show_instagram' => $request->has('show_instagram'),
                'show_facebook' => $request->has('show_facebook'),
                'show_telegram' => $request->has('show_telegram'),
                'show_whatsapp' => $request->has('show_whatsapp'),
            ]);
        }

        return redirect()->route('admin.footer-settings.edit')
            ->with('success', 'Configurações do rodapé atualizadas com sucesso!');
    }
    
    /**
     * Update individual footer setting field via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateField(Request $request)
    {
        try {
            $field = $request->field;
            $value = $request->value;
            
            // Determine if the field belongs to footer settings or social links
            $socialLinkFields = ['instagram', 'facebook', 'telegram', 'whatsapp', 
                                'show_instagram', 'show_facebook', 'show_telegram', 'show_whatsapp'];
            
            if (in_array($field, $socialLinkFields)) {
                // Update social link field
                $socialLink = SocialLink::first() ?: new SocialLink();
                
                // For checkboxes with boolean values
                if (in_array($field, ['show_instagram', 'show_facebook', 'show_telegram', 'show_whatsapp'])) {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
                
                $socialLink->$field = $value;
                $socialLink->save();
            } else {
                // For checkboxes with boolean values
                if (in_array($field, ['show_autorizado_cassino', 'show_social_links', 'show_topbar'])) {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
                
                // Update footer settings field
                $footerSettings = FooterSettings::getSettings();
                $footerSettings->$field = $value;
                $footerSettings->save();
            }
            
            return response()->json(['success' => true, 'message' => 'Campo atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar o campo: ' . $e->getMessage()], 422);
        }
    }
} 