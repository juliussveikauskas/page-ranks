import GuestLayout from '@/Layouts/GuestLayout';
import {Head, router} from '@inertiajs/react';
import Pagination from "@/Components/Pagination.jsx";
import TextInput from "@/Components/TextInput.jsx";

export default function Index({pages, queryParams = null}) {
    queryParams = queryParams || {};

    const searchFieldChanged = (field, value) => {
        if (value) {
            queryParams[field] = value;
            if(queryParams.page) delete queryParams.page;
        } else {
            delete queryParams[field];
        }

        router.get(route('pages.index', queryParams));
    }

    const onKeyPress = (field, e) => {
        if (e.key !== 'Enter') return;
        searchFieldChanged(field, e.target.value);
    }

    return (
        <GuestLayout>
            <Head title="Pages list"/>

            <table className="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th className="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Domain
                    </th>
                    <th className="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Rank
                    </th>
                </tr>
                </thead>
                <thead>
                <tr>
                    <th className="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        <TextInput
                            className="w-full"
                            defaultValue={queryParams.domain}
                            placeholder="Search..."
                            onBlur={e => searchFieldChanged('domain', e.target.value)}
                            onKeyPress={e => onKeyPress('domain', e)}
                        />
                    </th>
                    <th className="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                {pages.data.map((page, index) => (
                    <tr key={index}>
                        <td className="px-6 py-4 whitespace-no-wrap">
                            <div className="flex items-center">
                                {page.domain}
                            </div>
                        </td>
                        <td className="px-6 py-4 whitespace-no-wrap">
                            <div className="text-sm leading-5 text-gray-900">
                                {page.rank}
                            </div>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
            <Pagination links={pages.meta.links}/>
        </GuestLayout>
    );
}
