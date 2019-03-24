from django.urls import include, path

urlpatterns = [
    path('', include('website.landing.urls')),
]
