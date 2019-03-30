import os
from environ import Env, Path

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Environment variables
env = Env(
    DEBUG=(bool, False)
)
env.read_env(BASE_DIR + '/.env')

SECRET_KEY = env('SECRET')

DEBUG = env('DEBUG')

ALLOWED_HOSTS = ['robinbisping.com', 'www.robinbisping.com', 'localhost', '127.0.0.1']

INSTALLED_APPS = [
    'website.landing',
    'django.contrib.staticfiles',
]

MIDDLEWARE = [
    'django.middleware.security.SecurityMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',
]

ROOT_URLCONF = 'website.urls'

TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [],
        'APP_DIRS': True,
        'OPTIONS': {
            'context_processors': [
                'django.template.context_processors.debug',
                'django.template.context_processors.request',
            ],
        },
    },
]

CACHES = {
    'default': {
        'BACKEND': 'django.core.cache.backends.filebased.FileBasedCache',
        'LOCATION': '/var/tmp/django',
    }
}

WSGI_APPLICATION = 'website.wsgi.application'

STATIC_URL = '/static/'
STATIC_ROOT = BASE_DIR + '/static'
