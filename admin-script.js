jQuery(document).ready(function ($) {
    const representativeFieldsContainer = $('#representative_fields_container');

    // Add field button event
    $('#add_representative_field_button').on('click', function () {
        const fieldHTML = `
        <div class="representative_field">
            <p>
                <label>Position:</label>
                <input type="text" name="representative_position[]">
            </p>
            <p>
                <label>Name:</label>
                <input type="text" name="representative_name[]">
            </p>
            <p>
                <label>Email:</label>
                <input type="email" name="representative_email[]">
            </p>
            <p>
                <label>Phone:</label>
                <input type="text" name="representative_phone[]">
            </p>
            <p>
                <label>Address:</label>
                <textarea name="representative_address[]"></textarea>
            </p>
            <p>
                <label>Photo:</label>
                <input type="hidden" class="image-url" name="representative_photo[]">
                <img src="" class="image-preview" style="max-width: 100px;">
                <input type="button" class="upload-image-button" value="Upload Image">
            </p>
            <p class="action_buttons remove">
                <i class="fa-solid fa-2xl fa-square-minus"></i>
                <input type="button" class="remove_representative_field_button" value="Remove Field">
            </p>
            
            <hr>
        </div>`;

        representativeFieldsContainer.append(fieldHTML);
    });

    // Remove field button event
    representativeFieldsContainer.on('click', '.remove_representative_field_button', function () {
        $(this).parent().parent('.representative_field').remove();
    });

    // Image upload
    representativeFieldsContainer.on('click', '.upload-image-button', function (e) {
        e.preventDefault();

        const button = $(this);
        const imagePreview = button.siblings('.image-preview');
        const imageUrlInput = button.siblings('.image-url');

        const file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        file_frame.on('select', function () {
            const attachment = file_frame.state().get('selection').first().toJSON();

            imageUrlInput.val(attachment.url);
            imagePreview.attr('src', attachment.url);
        });

        file_frame.open();
    });


    const dealerFieldsContainer = $('#dealer_fields_container');

    // Add field button event
    $('#add_dealer_field_button').on('click', function () {
        const fieldHTML = `
        <div class="dealer_field">
            <p>
                <label>Name:</label>
                <input type="text" name="dealer_name[]">
            </p>
            <p>
                <label>Email:</label>
                <input type="email" name="dealer_email[]">
            </p>
            <p>
                <label>Website:</label>
                <input type="text" name="dealer_website[]">
            </p>
            <p>
                <label>Phone:</label>
                <input type="text" name="dealer_phone[]">
            </p>
            <p>
                <label>Fax:</label>
                <input type="text" name="dealer_fax[]">
            </p>
            <p>
                <label>Address:</label>
                <textarea name="dealer_address[]"></textarea>
            </p>
            <p>
                <label>Photo:</label>
                <input type="hidden" class="image-url" name="dealer_photo[]">
                <img src="" class="image-preview" style="max-width: 100px;">
                <input type="button" class="upload-image-button" value="Upload Image">
            </p>
            <p class="action_buttons remove">
                <i class="fa-solid fa-2xl fa-square-minus"></i>
                <input type="button" class="remove_dealer_field_button" value="Remove Field">
            </p>
            <hr>
        </div>`;

        dealerFieldsContainer.append(fieldHTML);
    });

    // Remove field button event
    dealerFieldsContainer.on('click', '.remove_dealer_field_button', function () {
        $(this).parent().parent('.dealer_field').remove();
    });

    // Image upload
    dealerFieldsContainer.on('click', '.upload-image-button', function (e) {
        e.preventDefault();

        const button = $(this);
        const imagePreview = button.siblings('.image-preview');
        const imageUrlInput = button.siblings('.image-url');

        const file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        file_frame.on('select', function () {
            const attachment = file_frame.state().get('selection').first().toJSON();

            imageUrlInput.val(attachment.url);
            imagePreview.attr('src', attachment.url);
        });

        file_frame.open();
    });
});