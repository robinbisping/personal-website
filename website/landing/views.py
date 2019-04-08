from django.http import Http404
from django.shortcuts import render


def home(request, language=None):
    """
    Homepage
    """
    # Available languages
    languages = [
        ('de', 'Deutsch'),
        ('en', 'English'),
    ]

    # Set language to english if none is given
    if language is None:
        language = 'en'

    # Render template if the language is defined
    if language in [lang[0] for lang in languages]:
        context = {
            'language': language,
            'languages': languages,
        }
        return render(request, 'index.html', context=context)

    # Language not found
    raise Http404
