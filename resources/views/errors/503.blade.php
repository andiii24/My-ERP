<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title> SmartWork Updating ... </title>
    @include('assets.css')
    @include('pwa.tags')
</head>

<body>
    <main>
        <section class="hero bg-lightgreen is-fullheight">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title text-green has-text-weight-light">
                        <span class="icon is-large">
                            <i class="fa-solid fa-rocket"></i>
                        </span>
                        <span class="is-size-1">
                            <b>SmartWork</b> UPDATING ...
                        </span>
                    </h1>
                    <h1 class="title text-green">
                        <span>
                            Sorry for the interruption.
                        </span>
                    </h1>
                    <h2 class="subtitle has-text-grey-light has-text-weight-normal">
                        We are releasing new updates. It will take 1 or 2 minutes.
                        <br>
                        If it is taking longer, please call the helpdesk at +251 97-600-6522.
                    </h2>
                    <button
                        x-data
                        class="button btn-green is-outlined is-uppercase has-text-weight-medium px-5 py-5"
                        @click="history.back()"
                    >
                        <span class="icon">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <span>
                            Go Back
                        </span>
                    </button>
                    <button
                        x-data
                        class="button bg-green has-text-white is-uppercase has-text-weight-medium px-5 py-5"
                        @click="location.reload()"
                    >
                        <span class="icon">
                            <i class="fas fa-redo-alt"></i>
                        </span>
                        <span>
                            Refresh
                        </span>
                    </button>
                </div>
            </div>
        </section>
    </main>
    @include('assets.js')
</body>

</html>
