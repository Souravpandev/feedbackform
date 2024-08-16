To create a feedback form in your wordpress webiste without plugin paste this code in your childe theme functions.php:

here is the short code to display the form:

[feedback_button_modal]



# feedbackform

This code snippet sets up a feedback system in WordPress, including a custom post type for storing feedback, a shortcode for displaying a feedback form with a modal popup, and functionality to handle feedback submissions and display them in the admin area. Here's a breakdown:

1. Custom Post Type Registration (register_feedback_post_type)
Purpose: Registers a custom post type called feedback.
Labels: Defines various labels for the feedback post type, such as "Add New Feedback" and "View Feedback".
Arguments ($args): Configures the post type with various settings:
public and publicly_queryable set to false (not accessible directly on the site front-end).
show_ui and show_in_menu set to true (appears in the WordPress admin interface).
rewrite sets the URL slug for feedback.
Supports title and editor (title and content fields).
2. Feedback Form Shortcode (feedback_form_shortcode)
Purpose: Creates a shortcode to display a feedback button and a modal form.
HTML/CSS: The modal includes a form for users to submit their feedback, including name, email, type of feedback, rating, and a message.
JavaScript: Handles the opening and closing of the modal:
Opens the modal when the button is clicked.
Closes the modal when the close button or outside the modal is clicked.
3. Feedback Submission Handling (handle_feedback_submission)
Purpose: Processes the form submission and saves the feedback as a custom post type entry.
Data Sanitization: Ensures the input is safe using sanitize_text_field, sanitize_email, and sanitize_textarea_field.
Post Creation: Uses wp_insert_post to create a new feedback entry.
Thank You Message: Displays a thank you message upon submission.
4. Custom Columns in Admin (set_feedback_columns, custom_feedback_column)
Purpose: Customizes the columns displayed in the admin list view for the feedback post type.
Column Definitions: Adds columns for name, email, type, rating, and message.
Column Data Display: Populates the columns with data from the feedback posts, including mapping rating values to emojis.
5. Admin Menu Integration (feedback_admin_menu)
Purpose: Adds a menu item to the WordPress admin sidebar for the feedback post type.
Menu Page: Uses add_menu_page to create a top-level menu item with a feedback icon.

