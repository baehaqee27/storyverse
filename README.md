# StoryVerse 📖

StoryVerse is a modern, immersive web platform for reading and writing novels. Built with **Laravel**, **Livewire**, and **Tailwind CSS**, it offers a premium reading experience with customizable settings, social engagement features, and a powerful admin panel.

![StoryVerse Banner](https://via.placeholder.com/1200x600?text=StoryVerse+Preview)

## 🚀 Features

### 📚 For Readers
- **Immersive Reading Mode**: Distraction-free reading with customizable settings:
    - **Themes**: Light, Sepia, Dark.
    - **Typography**: Adjustable font size and paragraph indentation.
    - **Font**: "Merriweather" for content, "Outfit" for UI.
- **Engagement**:
    - **Like** chapters to show appreciation.
    - **Comment** on chapters to discuss with the community.
    - **Share** chapters via Facebook, Twitter/X, WhatsApp, or Copy Link.
- **Follow Authors**: Keep up with your favorite writers.
- **Responsive Design**: Optimized for desktop, tablet, and mobile.

### ✍️ For Authors & Admins
- **Novel Management**: Create, edit, and manage novels with rich text support (Trix Editor).
- **Chapter Management**: Organize stories into chapters.
- **Status Control**: Set novels as Ongoing, Completed, or Hiatus.
- **Cover Management**: Upload and manage novel covers with auto-cleanup for old files.
- **Admin Dashboard**: Built with **Filament PHP** for managing Users, Novels, and Genres.
    - Custom branded UI (Indigo theme, Outfit font).
    - Collapsible sidebar and optimized layout.

### 🔐 Authentication & Security
- **Secure Auth**: Powered by Laravel Breeze.
- **Social Login**: Google OAuth integration.
- **Email Verification**: Custom-designed verification flow.
- **Role-Based Access**: Admin and User roles.

## 🛠️ Tech Stack

- **Framework**: [Laravel 11](https://laravel.com)
- **Frontend**: [Blade](https://laravel.com/docs/blade), [Tailwind CSS](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)
- **Reactivity**: [Livewire 3](https://livewire.laravel.com) (Volt Class API)
- **Admin Panel**: [Filament PHP](https://filamentphp.com)
- **Database**: MySQL / SQLite
- **Icons**: Heroicons

## ⚙️ Installation

Follow these steps to set up the project locally:

1.  **Clone the repository**
    ```bash
    git clone https://github.com/baehaqee27/storyverse.git
    cd storyverse
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    Copy the `.env.example` file to `.env` and configure your database credentials.
    ```bash
    cp .env.example .env
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Run Migrations & Seeders**
    ```bash
    php artisan migrate --seed
    ```

6.  **Link Storage**
    ```bash
    php artisan storage:link
    ```

7.  **Run the Application**
    Start the development server and asset bundler:
    ```bash
    npm run dev
    # In a separate terminal
    php artisan serve
    ```

    Visit `http://localhost:8000` to view the app.
    Visit `http://localhost:8000/admin` to access the admin panel.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
