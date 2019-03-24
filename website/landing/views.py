from django.conf import settings
from django.shortcuts import render, HttpResponse
from django.utils import translation


def home(request, language=None):

    # Let browser choose language
    if language is None:
        return render(request, 'index.html')

    # Activate language if it exists
    if language in [element[0] for element in settings.LANGUAGES]:
        translation.activate(language)
        return render(request, 'index.html')

    # Language not found, return 404 error
    return HttpResponse(status=404)
