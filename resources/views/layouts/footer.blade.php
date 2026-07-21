<footer class="main-footer">
    <div class="footer-build-info pull-right hidden-xs">
        <span>
            <i class="fa fa-code-fork"></i>
            Mukahlera Build <span class="footer-version">v{{ env('PORTAL_VERSION', '1.0.0') }}</span>
        </span>
    </div>

    <div class="footer-links">
        <strong class="footer-copyright">
            Copyright &copy; {{ date('Y') }}
            <a href="https://www.elias.co.zw" class="footer-brand-link">
                {{ config('app.name') }}
            </a>
        </strong>
        <span class="footer-rights">
            All rights reserved.
        </span>
    </div>
</footer>

<style>
    .main-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #f1f5f9;
        background: #fff;
        padding: 20px 25px;
        color: #64748b;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .footer-build-info {
        color: #94a3b8;
        letter-spacing: 0.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .footer-build-info .fa-code-fork {
        margin-right: 6px;
        color: #cbd5e1;
    }

    .footer-version {
        color: #3c8dbc;
        font-weight: 700;
        margin-left: 2px;
    }

    .footer-links {
        display: flex;
        align-items: center;
    }

    .footer-copyright {
        color: #1e293b;
        font-weight: 600;
    }

    .footer-brand-link {
        color: #3c8dbc;
        text-decoration: none;
        font-weight: 700;
        transition: color 0.2s ease;
    }

    .footer-brand-link:hover {
        color: #23527c;
        text-decoration: none;
    }

    .footer-rights {
        margin-left: 10px;
        padding-left: 10px;
        border-left: 1px solid #e2e8f0;
        color: #94a3b8;
    }

    @media (max-width: 767px) {
        .main-footer {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        .footer-build-info {
            justify-content: center;
        }
        .footer-rights {
            border-left: none;
            display: block;
            margin-left: 0;
            padding-left: 0;
            margin-top: 5px;
        }
    }
</style>
