import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Dashboard({ auth, users, readFrom }) {

    console.log(users)
    console.log(readFrom)
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Home</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className='table_container'>
                            <table className='table-auto w-full text-left' id='user_table'>
                                <caption className="caption-top mb-8">
                                    User data loaded from: { readFrom } 
                                </caption>                                
                                <thead className='border-b-2 border-gray-300'>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>E-mail</th>
                                        <th>Birthdate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    { users.map((user) => (
                                    <tr key={ user.id } >
                                        <td>{ user.id }</td>
                                        <td>{ user.name }</td>
                                        <td>{ user.email }</td>
                                        <td>{ user.birthdate }</td>
                                    </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
