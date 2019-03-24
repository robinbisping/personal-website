from django.http import Http404
from django.shortcuts import render


def home(request, language=None):
    """
    Homepage
    """
    # Let browser choose language if none is given
    if language is None:
        return home(request, request.META['HTTP_ACCEPT_LANGUAGE'])

    # Available languages
    languages = [
        ('de', 'Deutsch'),
        ('en', 'English'),
    ]

    # Render template if the language is defined
    if language in [lang[0] for lang in languages]:
        context = {
            'language': language,
            'languages': languages,
        }
        return render(request, 'index.html', context=context)

    # Language not found
    raise Http404
