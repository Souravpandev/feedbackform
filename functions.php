function register_feedback_post_type() {
    $labels = array(
        'name'               => 'Feedbacks',
        'singular_name'      => 'Feedback',
        'menu_name'          => 'Feedbacks',
        'name_admin_bar'     => 'Feedback',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Feedback',
        'new_item'           => 'New Feedback',
        'edit_item'          => 'Edit Feedback',
        'view_item'          => 'View Feedback',
        'all_items'          => 'All Feedbacks',
        'search_items'       => 'Search Feedbacks',
        'not_found'          => 'No feedback found.',
        'not_found_in_trash' => 'No feedback found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'feedback'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor'),
    );

    register_post_type('feedback', $args);
}
add_action('init', 'register_feedback_post_type');

function feedback_form_shortcode() {
    ob_start();
    ?>
    <!-- Feedback Button -->
    <button id="feedback-button" class="feedback-button"> Feedback</button>

    <!-- Feedback Modal -->
    <div id="feedback-modal" class="feedback-modal">
        <div class="feedback-modal-content">
            <span class="feedback-close">&times;</span>
            <form id="feedback-form" method="post">
                <label for="feedback-name">Name:</label><br>
                <input type="text" id="feedback-name" name="feedback_name" required><br><br>

                <label for="feedback-email">Email:</label><br>
                <input type="email" id="feedback-email" name="feedback_email" required><br><br>

                <label for="feedback-type">Type of Feedback:</label><br>
                <select id="feedback-type" name="feedback_type" required>
                    <option value="">Select a type (Required)</option>
                    <option value="Factual Correction">Factual Correction</option>
                    <option value="Spelling/Grammar Correction">Spelling/Grammar Correction</option>
                    <option value="Link Correction">Link Correction</option>
                    <option value="Suggest Improvement">Suggest Improvement</option>
                    <option value="Other">Other</option>
                </select><br><br>

                <label>Rating:</label><br>
                <label>
                    <input type="radio" name="feedback_rating" value="very_good" required>
                    😀 Very Good
                </label><br>
                <label>
                    <input type="radio" name="feedback_rating" value="good" required>
                    🙂 Good
                </label><br>
                <label>
                    <input type="radio" name="feedback_rating" value="neutral" required>
                    😐 Neutral
                </label><br>
                <label>
                    <input type="radio" name="feedback_rating" value="bad" required>
                    🙁 Bad
                </label><br>
                <label>
                    <input type="radio" name="feedback_rating" value="need_improvement" required>
                    😕 Need to Improve
                </label><br><br>

                <label for="feedback-message">Feedback:</label><br>
                <textarea id="feedback-message" name="feedback_message" required></textarea><br><br>

                <input type="submit" name="feedback_submit" value="Submit Feedback">
            </form>
        </div>
    </div>

    <style>
    .feedback-social {
    display: flex;
    align-items: center;
    gap: 15px;
}
        /* Modal styles */
        .feedback-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .feedback-modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        .feedback-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .feedback-close:hover,
        .feedback-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
       
.feedback-button {
    padding: 5px 11px;
    background-color: #ffffff;
    color: black;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 1px solid #757575;
}
    </style>

    <script>
        // Get modal element
        var modal = document.getElementById('feedback-modal');
        
        // Get button that opens the modal
        var btn = document.getElementById('feedback-button');
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName('feedback-close')[0];
        
        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = 'block';
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = 'none';
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('feedback_button_modal', 'feedback_form_shortcode');

function handle_feedback_submission() {
    if (isset($_POST['feedback_submit'])) {
        $name = sanitize_text_field($_POST['feedback_name']);
        $email = sanitize_email($_POST['feedback_email']);
        $type = sanitize_text_field($_POST['feedback_type']);
        $rating = sanitize_text_field($_POST['feedback_rating']);
        $message = sanitize_textarea_field($_POST['feedback_message']);

        $feedback_data = array(
            'post_title'   => $name,
            'post_content' => $message,
            'post_status'  => 'publish',
            'post_type'    => 'feedback',
            'meta_input'   => array(
                'feedback_email' => $email,
                'feedback_type'  => $type,
                'feedback_rating' => $rating,
            ),
        );

        wp_insert_post($feedback_data);

        // Optionally, display a thank you message
        echo '<p>Thank you for your feedback!</p>';
    }
}
add_action('wp', 'handle_feedback_submission');

function set_feedback_columns($columns) {
    unset($columns['date']);
    $columns['feedback_name'] = 'Name';
    $columns['feedback_email'] = 'Email';
    $columns['feedback_type'] = 'Type';
    $columns['feedback_rating'] = 'Rating';
    $columns['feedback_message'] = 'Feedback';

    return $columns;
}
add_filter('manage_feedback_posts_columns', 'set_feedback_columns');

function custom_feedback_column($column, $post_id) {
    switch ($column) {
        case 'feedback_name':
            echo get_the_title($post_id);
            break;
        case 'feedback_email':
            echo get_post_meta($post_id, 'feedback_email', true);
            break;
        case 'feedback_type':
            echo get_post_meta($post_id, 'feedback_type', true);
            break;
        case 'feedback_rating':
            $rating = get_post_meta($post_id, 'feedback_rating', true);
            $emoji = array(
                'very_good' => '😀 Very Good',
                'good'      => '🙂 Good',
                'neutral'   => '😐 Neutral',
                'bad'       => '🙁 Bad',
                'need_improvement' => '😕 Need to Improve',
            );
            echo isset($emoji[$rating]) ? $emoji[$rating] : 'Not Rated';
            break;
        case 'feedback_message':
            echo get_the_excerpt($post_id);
            break;
    }
}
add_action('manage_feedback_posts_custom_column', 'custom_feedback_column', 10, 2);

function feedback_admin_menu() {
    add_menu_page(
        'Feedback',
        'Feedback',
        'manage_options',
        'edit.php?post_type=feedback',
        '',
        'dashicons-feedback',
        25
    );
}
add_action('admin_menu', 'feedback_admin_menu');
