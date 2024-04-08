import {Link} from "@inertiajs/react";

export default function Pagination({links}) {
    return (
        <nav className="bg-white px-4 py-3 flex items-center text-center border-t border-gray-200 sm:px-6">
            {links.map((link, index) => (
                <div key={index}>
                    <Link
                        preserveScroll
                        dangerouslySetInnerHTML={{__html: link.label}}
                        href={link.url || "#"}
                        className={
                            "relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" +
                            (link.active ? " z-10 bg-gray-100 " : "") +
                            (!link.url ? " hidden" : "hover:bg-gray-50")
                        }
                    >
                    </Link>
                </div>
            ))}
        </nav>
    )
}
